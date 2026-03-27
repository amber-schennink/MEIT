<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stripe\StripeClient;
use App\Mail\NieuweCeremonieAanmelding;
use App\Mail\BevestigingCeremonieAanmelding;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use DateTime;

class CeremonieCheckoutController extends Controller
{
    protected ?StripeClient $stripe = null;

    public function __construct()
    {
        // Alleen Stripe client aanmaken als we niet in fake mode zijn EN er een key is
        $skipPayment = filter_var(env('PAYMENT_FAKE', false), FILTER_VALIDATE_BOOLEAN);
        $stripeSecret = config('services.stripe.secret');
        
        if (!$skipPayment && !empty($stripeSecret)) {
          $this->stripe = new StripeClient($stripeSecret);
        }
    }

    public function start(Request $request)
    {
      $request->validate([
        'id_ceremonie'  => 'required|integer',
        'betaal_optie' => 'required|in:0,1,2', // 0=deels, 1=contant betaald, 2=volledig via betaal link
      ]);

      $ceremonieId = (int) $request->input('id_ceremonie');
      $ceremonie   = DB::table('ceremonies')->where('id', $ceremonieId)->first();
      abort_unless($ceremonie, 404);
      $backUrl = $request->input('back', url('/ceremonie/'.$ceremonie->id));

      if($ceremonie->id_deelnemer){
        return redirect($backUrl)->with('msg', 'Er is al iemand ingeschreven voor deze ceremonie');
      }

      // Volgende pagina (optioneel hidden input 'next' in je form)
      $nextUrl = $request->input('next', url('/overzicht'));

      // Absolute URLs voor Stripe
      $successUrl = route('ceremonie_checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&next=' . urlencode($nextUrl);
      $cancelUrl  = url()->previous();

      $idDeelnemer = $this->handleDeelnemerData($request);
      $deelnemer = DB::table('deelnemers')
        ->where('id', $idDeelnemer)
        ->orderByDesc('id')
        ->first();

      $prijsEuro   = (float) Config::get('info.prijs');
      $amountFull  = (int) round($prijsEuro * 100);
      $amountHalf  = (int) round(($prijsEuro / 2) * 100);
      $betaalOptie = (int) $request->input('betaal_optie');

      // 🔁 FAKE mode (true = Stripe overslaan)
      $skipPayment = filter_var(env('PAYMENT_FAKE', false), FILTER_VALIDATE_BOOLEAN);

      // Stripe customer (alleen aanmaken als we NIET faken)
      if ($skipPayment) {
        $customerId = 'fake_'.bin2hex(random_bytes(8));
      } else {
        $customerId = $this->getOrCreateCustomer($email ?? null, $naam ?? null);
      }

      // ───────────────── Aanmelding ophalen of aanmaken ─────────────────

      // Basisvelden alvast updaten (klant, email, due/remaining etc) zodat de betaalflow klopt
      DB::table('ceremonies')->where('id', $ceremonieId)->update([
        'pending_deelnemer_id' => $idDeelnemer,
        'stripe_customer_id'   => $customerId,
        'customer_email'       => $deelnemer->email,
        'updated_at'           => now(),
      ]);

      session([
        'pending_ceremonie_aanmelding_id' => $ceremonieId,
        'pending_deelnemer_id'  => $idDeelnemer,
      ]);
      $cancelUrl = route('ceremonie_checkout.cancel') . '?back=' . urlencode($backUrl);


      // ✅ FAKE payment pad (geen Stripe calls)
      if ($skipPayment) {
        $ceremonie = DB::table('ceremonies')->where('id', $ceremonieId)->first();
        if ($betaalOptie === 2) {
          // Simuleer volledige betaling
          DB::table('ceremonies')->where('id', $ceremonieId)->update([
            'id_deelnemer'         => $ceremonie->pending_deelnemer_id,
            'pending_deelnemer_id' => null,
            'betaal_status'        => 2,
            'amount_paid'          => DB::raw('amount_paid + '.$amountFull),
            'updated_at'           => now(),
          ]);
        }

        if ($betaalOptie === 0) {
            // Simuleer half contant betaling
            DB::table('ceremonies')->where('id', $ceremonieId)->update([
                'id_deelnemer'         => $ceremonie->pending_deelnemer_id,
                'pending_deelnemer_id' => null,
                'betaal_status'        => 0,
                'amount_paid'          => DB::raw('amount_paid + '.$amountHalf),
                'updated_at'           => now(),
            ]);
        }

        // Verstuur emails ook bij fake payment
        $deelnemer_mail = DB::table('deelnemers')->where('id', $idDeelnemer)->first();
        $ceremonie_mail = DB::table('ceremonies')->where('id', $ceremonieId)->first();

        // try {
        //   // Email naar admin bij nieuwe aanmelding
        //   $adminEmail = Config::get('info.admin_email');
        //   if ($adminEmail) {
        //     Mail::to($adminEmail)->send(new NieuweCeremonieAanmelding($deelnemer_mail, $ceremonie_mail));
        //     Log::info('Admin email verstuurd naar: ' . $adminEmail);
        //   }

        //   // Bevestigingsmail naar deelnemer
        //   if ($deelnemer_mail && $deelnemer_mail->email) {
        //     Mail::to($deelnemer_mail->email)->send(new BevestigingCeremonieAanmelding($deelnemer_mail, $ceremonie_mail));
        //     Log::info('Bevestigingsmail verstuurd naar: ' . $deelnemer_mail->email);
        //   }
        // } catch (\Exception $e) {
        //   Log::error('Email versturen mislukt: ' . $e->getMessage());
        // }

        $msg = ($betaalOptie === 2) ? 'Betaling voltooid (fake).' : 'Aanbetaling ontvangen (fake).';
        return redirect($nextUrl)->with('msg', $msg);
      }

      $checkoutDescription = "Met deze aanbetaling is jouw plek officieel bevestigd. De overige €222,- betaal je contant op de dag van je ceremonie. "
        ."Na betaling ontvang je een bevestigingsmail en neem ik snel contact met je op!";

      $checkoutImageUrl = secure_asset('assets/logo.png');

      // ───────────── ECHTE STRIPE CHECKOUT ─────────────

      // Volledige betaling
      if ($betaalOptie === 2) {
        $session = $this->stripe->checkout->sessions->create([
          'mode'     => 'payment',
          'customer' => $customerId,
          'payment_method_types' => ['ideal', 'card', 'klarna', 'bancontact'],
          'payment_intent_data' => [
            'metadata' => [
              'ceremonie_id' => (string) $ceremonieId,
              'betaal_optie'  => 'volledig',
            ],
          ],
          'line_items' => [[
            'quantity' => 1,
            'price_data' => [
                'currency'    => 'eur',
                'unit_amount' => $amountFull,
                'product_data'=> [
                  'name'        => 'MEIT. Ceremonie',                // titel links
                  'description' => $checkoutDescription,             // tekst onder de prijs
                  'images'      => [$checkoutImageUrl],              // grote afbeelding (phoenix)
                ],
              ],
          ]],
          'success_url' => $successUrl,
          'cancel_url'  => $cancelUrl,
        ]);

        DB::table('ceremonies')->where('id', $ceremonieId)->update([
          'stripe_checkout_session_id' => $session->id,
          'updated_at' => now(),
        ]);

        return redirect($session->url);
      }

      // Deels contant
      $session = $this->stripe->checkout->sessions->create([
        'mode'     => 'payment',
        'customer' => $customerId,
        'payment_method_types' => ['ideal', 'card', 'klarna', 'bancontact'],
        'payment_intent_data' => [
          'metadata' => [
            'ceremonie_id' => (string) $ceremonieId,
            'betaal_optie'  => 'deels',
          ],
        ],
        'line_items' => [[
          'quantity' => 1,
          'price_data' => [
            'currency'    => 'eur',
            'unit_amount' => $amountHalf,
            'product_data'=> [
              'name'        => 'MEIT. Ceremonie aanbetaling',
              'description' => $checkoutDescription,
              'images'      => [$checkoutImageUrl],
            ],
          ],
        ]],
        'success_url' => $successUrl,
        'cancel_url'  => $cancelUrl,
      ]);

      DB::table('ceremonies')->where('id', $ceremonieId)->update([
        'stripe_checkout_session_id' => $session->id,
        'updated_at' => now(),
      ]);

      return redirect($session->url);
  }

  public function success(Request $request)
  {
      $sessionId = $request->query('session_id');
      $next      = $request->query('next', url('/overzicht'));
      abort_unless($sessionId, 400);

      $session = $this->stripe->checkout->sessions->retrieve(
        $sessionId,
        ['expand' => ['payment_intent']]
      );

      $ceremonie = DB::table('ceremonies')
        ->where('stripe_checkout_session_id', $session->id)
        ->first();
      abort_unless($ceremonie, 404);

      if ($session->payment_status !== 'paid') {
        return redirect($next)->with('error', 'Betaling niet voltooid.');
      }

      $pi              = $session->payment_intent;
      $amountReceived  = (int) ($pi->amount_received ?? 0);
      $paymentMethodId = $pi->payment_method ?? null;

      $prijsEuro    = (float) Config::get('info.prijs');
      $totaalCents  = (int) round($prijsEuro * 100);
      $nieuweStatus = ($amountReceived >= $totaalCents) ? 2 : 0; // 2=volledig, 0=deels

      if ($ceremonie->stripe_payment_intent_id === $pi->id) {
        return redirect($next)->with('msg', 'Betaling was al verwerkt.');
      }

      DB::table('ceremonies')->where('id', $ceremonie->id)->update([
        'id_deelnemer'             => $ceremonie->pending_deelnemer_id,
        'pending_deelnemer_id'     => null,
        'betaal_status'            => $nieuweStatus,
        'amount_paid'              => DB::raw('amount_paid + '.$amountReceived),
        'stripe_payment_intent_id' => $pi->id,
        'stripe_payment_method_id' => $paymentMethodId,
        'updated_at'               => now(),
      ]);

      // Haal deelnemer en training op voor de emails
      $ceremonie_mail = DB::table('ceremonies')->where('id', $ceremonie->id)->first();
      $deelnemer_mail = DB::table('deelnemers')->where('id', $ceremonie_mail->id_deelnemer)->first();

      // Verstuur emails
      try {
        // Email naar admin bij nieuwe aanmelding
        $adminEmail = Config::get('info.admin_email');
        if ($adminEmail) {
          Mail::to($adminEmail)->send(new NieuweCeremonieAanmelding($deelnemer_mail, $ceremonie_mail));
        }

        // Bevestigingsmail naar deelnemer
        if ($deelnemer_mail && $deelnemer_mail->email) {
          Mail::to($deelnemer_mail->email)->send(new BevestigingCeremonieAanmelding($deelnemer_mail, $ceremonie_mail));
        }
      } catch (\Exception $e) {
        // Log de fout maar laat de redirect doorgaan
        Log::error('Email versturen mislukt: ' . $e->getMessage());
      }

      return redirect($next)->with('msg', $nieuweStatus === 2 ? 'Betaling voltooid.' : 'Aanbetaling ontvangen.');
  }

  public function cancel(Request $request)
  {
    $back = $request->query('back', url('/ceremonie_aanmelden'));

    $ceremonieId = (int) session('pending_ceremonie_aanmelding_id');
    $deelnemerId  = (int) session('pending_deelnemer_id');

    // 1) aanmelding verwijderen als die nog "pending" is
    if ($ceremonieId) {
      $ceremonie = DB::table('ceremonies')->where('id', $ceremonieId)->first();

      // if ($ceremonie && (int) $ceremonie->betaal_status === 0) {
      //   DB::table('ceremonies')->where('id', $ceremonieId)->update([
      //     'id_deelnemer'         => NULL,
      //     'stripe_customer_id'   => NULL,
      //     'customer_email'       => NULL,
      //     'updated_at'           => now(),
      //   ]);
      // }

      if ($ceremonie && !empty($ceremonie->stripe_checkout_session_id) && $this->stripe) {
        try {
          $this->stripe->checkout->sessions->expire($ceremonie->stripe_checkout_session_id);
        } catch (\Throwable $e) {
          Log::warning('Stripe session expire mislukt of was al afgerond: '.$e->getMessage());
        }
      }

      $this->resetPendingCeremonie($ceremonieId);
    }

    // 2) deelnemer verwijderen, maar alleen als hij verder nergens aan gekoppeld is
    // if ($deelnemerId) {
    //   $heeftNogAanmeldingen = DB::table('aanmeldingen')
    //     ->where('id_deelnemer', $deelnemerId)
    //     ->exists();

    //   if (!$heeftNogAanmeldingen) {
    //     DB::table('deelnemers')->where('id', $deelnemerId)->delete();
    //   }
    // }

    // Uitloggen + sessie opschonen
    session()->forget([
      'pending_ceremonie_aanmelding_id',
      'pending_deelnemer_id',
    ]);

    return redirect($back)->with('error', 'Betaling geannuleerd.');
  }

  private function getOrCreateCustomer(?string $email, ?string $name): string
  {
      if (!$email) {
          $c = $this->stripe->customers->create(['name' => $name ?: 'Onbekend']);
          return $c->id;
      }

      $existing = DB::table('ceremonies')
          ->where('customer_email', $email)
          ->whereNotNull('stripe_customer_id')
          ->value('stripe_customer_id');

      if ($existing) return $existing;

      $c = $this->stripe->customers->create([
          'email' => $email,
          'name'  => $name ?: null,
      ]);
      return $c->id;
  }
  private function resetPendingCeremonie(int $ceremonieId, ?string $expectedSessionId = null): void{
    $ceremonie = DB::table('ceremonies')->where('id', $ceremonieId)->first();

    if (!$ceremonie) {
      return;
    }

    if ($expectedSessionId && $ceremonie->stripe_checkout_session_id !== $expectedSessionId) {
      return;
    }

    // Alleen resetten als nog niet betaald
    if ($ceremonie->betaal_status !== NULL) {
      return;
    }

    DB::table('ceremonies')->where('id', $ceremonieId)->update([
      'id_deelnemer'               => null,
      'pending_deelnemer_id'       => null,
      'stripe_customer_id'         => null,
      'customer_email'             => null,
      'stripe_checkout_session_id' => null,
      'stripe_payment_intent_id'   => null,
      'stripe_payment_method_id'   => null,
      'updated_at'                 => now(),
    ]);
  }
  public function abandon(Request $request){
    $ceremonieId = (int) session('pending_ceremonie_aanmelding_id');

    if (!$ceremonieId) {
      return response()->json(['ok' => true]);
    }

    $ceremonie = DB::table('ceremonies')->where('id', $ceremonieId)->first();

    if ($ceremonie) {
      $expectedSessionId = $ceremonie->stripe_checkout_session_id ?? null;

      if ($expectedSessionId && $this->stripe) {
        try {
          $this->stripe->checkout->sessions->expire($expectedSessionId);
        } catch (\Throwable $e) {
          Log::warning('Stripe session expire mislukt of was al afgerond: '.$e->getMessage());
        }
      }

      $this->resetPendingCeremonie($ceremonieId, $expectedSessionId);
    }

    session()->forget([
      'pending_ceremonie_aanmelding_id',
      'pending_deelnemer_id',
    ]);

    return response()->json(['ok' => true]);
  }
}
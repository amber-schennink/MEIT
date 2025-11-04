<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stripe\StripeClient;
use DateTime;

class CheckoutController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'id_training'  => 'required|integer',
            'betaal_optie' => 'required|in:0,1,2', // 0=wachtlijst, 1=2 termijnen, 2=volledig
        ]);

        $trainingId = (int) $request->input('id_training');
        $training   = DB::table('trainingen')->where('id', $trainingId)->first();
        abort_unless($training, 404);

        // Volgende pagina (optioneel hidden input 'next' in je form)
        $nextUrl = $request->input('next', url('/overzicht'));

        // Absolute URLs voor Stripe
        $successUrl = route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&next=' . urlencode($nextUrl);
        $cancelUrl  = url()->previous();

        // --- deelnemer ophalen/aanmaken ---
        // if (session('login') && session('id')) {
        //     $deelnemer   = DB::table('deelnemers')->where('id', session('id'))->first();
        //     $idDeelnemer = $deelnemer->id;
        //     $email       = $deelnemer->email ?? null;
        //     $naam        = trim(($deelnemer->voornaam ?? '').' '.($deelnemer->tussenvoegsel ?? '').' '.($deelnemer->achternaam ?? '')) ?: 'Onbekend';
        // }elseif($request->deelnemer_type == 'login'){
        //   $request->validate([
        //       'login_email'      => 'required|email',
        //       'login_wachtwoord' => 'required|string',
        //   ]);

        //   $loginEmail = $request->input('login_email');
        //   $loginPass  = $request->input('login_wachtwoord');

        //   $deelnemer = DB::table('deelnemers')->where('email', $loginEmail)->first();
        //   abort_unless($deelnemer && \Illuminate\Support\Facades\Hash::check($loginPass, $deelnemer->wachtwoord), 401, 'Ongeldige inloggegevens.');

        //   // Sessie zetten
        //   session(['login' => true, 'id' => $deelnemer->id]);

        //   $idDeelnemer = (int) $deelnemer->id;
        //   $email       = $deelnemer->email ?? null;
        //   $naam        = trim(($deelnemer->voornaam ?? '').' '.($deelnemer->tussenvoegsel ?? '').' '.($deelnemer->achternaam ?? '')) ?: 'Onbekend';
        // } else {
        //     $request->validate([
        //         'deelnemer_voornaam'                  => 'required|string',
        //         'deelnemer_achternaam'                => 'required|string',
        //         'deelnemer_email'                     => 'required|email',
        //         'deelnemer_wachtwoord'                => 'required|string|min:8|same:deelnemer_wachtwoord-bevestiging',
        //     ]);

        //     $voornaam      = $request->input('deelnemer_voornaam');
        //     $tussenvoegsel = $request->input('deelnemer_tussenvoegsel');
        //     $achternaam    = $request->input('deelnemer_achternaam');
        //     $email         = $request->input('deelnemer_email');
        //     $telefoon      = $request->input('deelnemer_telefoon');
        //     $wachtwoord    = $request->input('deelnemer_wachtwoord');

        //     $idDeelnemer = DB::table('deelnemers')->insertGetId([
        //         'voornaam'        => $voornaam,
        //         'tussenvoegsel'   => $tussenvoegsel,
        //         'achternaam'      => $achternaam,
        //         'email'           => $email,
        //         'telefoon_nummer' => $telefoon,
        //         'wachtwoord'      => \Illuminate\Support\Facades\Hash::make($wachtwoord),
        //     ]);

        //     session(['login' => true, 'id' => $idDeelnemer]);
        //     $naam = trim("$voornaam ".($tussenvoegsel ?? '')." $achternaam");
        // }
        
        $idDeelnemer = $this->handleDeelnemerData($request);
        $deelnemer = DB::table('deelnemers')
            ->where('id', $idDeelnemer)
            ->orderByDesc('id')
            ->first();

        $prijsEuro   = (float) Config::get('info.prijs');
        $amountFull  = (int) round($prijsEuro * 100);
        $amountHalf  = (int) round(($prijsEuro / 2) * 100);
        $betaalOptie = (int) $request->input('betaal_optie');

        // Due date (7 dagen voor start)
        $dueAt = null;
        if (!empty($training->start_moment)) {
            $dt = new DateTime($training->start_moment);
            $dt->modify('-7 day');
            $dueAt = $dt->format('Y-m-d 00:00:00');
        }

        // 🔁 FAKE mode (true = Stripe overslaan)
        $skipPayment = filter_var(env('PAYMENT_FAKE', false), FILTER_VALIDATE_BOOLEAN);

        // Stripe customer (alleen aanmaken als we NIET faken)
        if ($skipPayment) {
            $customerId = 'fake_'.bin2hex(random_bytes(8));
        } else {
            $customerId = $this->getOrCreateCustomer($email ?? null, $naam ?? null);
        }

        // ───────────────── Aanmelding ophalen of aanmaken ─────────────────

        // Bestaat er al een aanmelding voor deze deelnemer+training?
        $existing = DB::table('aanmeldingen')
            ->where('id_deelnemer', $idDeelnemer)
            ->where('id_training',  $training->id)
            ->orderByDesc('id')
            ->first();

        if ($existing) {
            if ((int)$existing->betaal_status === 0) {
                // ⚠️ Stond op wachtlijst → hergebruik dit record i.p.v. nieuwe aanmelding te maken
                $aanmeldingId = (int) $existing->id;

                // Basisvelden alvast updaten (klant, email, due/remaining etc) zodat de betaalflow klopt
                DB::table('aanmeldingen')->where('id', $aanmeldingId)->update([
                    'amount_due_remaining' => ($betaalOptie === 1 ? $amountHalf : 0),
                    'due_at'               => ($betaalOptie === 1 ? $dueAt : null),
                    'stripe_customer_id'   => $customerId,
                    'customer_email'       => $deelnemer->email,
                    'updated_at'           => now(),
                ]);
            } else {
                // Al (deels) betaald → voorkom dubbele inschrijving
                return redirect($nextUrl)->with('msg', 'Je bent al aangemeld voor dit traject.');
            }
        } else {
            // Nog geen aanmelding → nieuw record
            $aanmeldingId = DB::table('aanmeldingen')->insertGetId([
                'id_deelnemer'         => $idDeelnemer,
                'id_training'          => $training->id,
                'betaal_status'        => 0, // start onbetaald / wachtlijst of vóór betalen
                'amount_paid'          => 0,
                'amount_due_remaining' => ($betaalOptie === 1 ? $amountHalf : 0),
                'due_at'               => ($betaalOptie === 1 ? $dueAt : null),
                'stripe_customer_id'   => $customerId,
                'customer_email'       => $deelnemer->email,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        // 0) Wachtlijst
        if ($betaalOptie === 0) {
          return redirect($nextUrl)->with('msg', 'Je staat op de wachtlijst.');
        }

        // ✅ FAKE payment pad (geen Stripe calls)
        if ($skipPayment) {
            if ($betaalOptie === 2) {
                // Simuleer volledige betaling
                DB::table('aanmeldingen')->where('id', $aanmeldingId)->update([
                    'betaal_status'        => 2,
                    'amount_paid'          => DB::raw('amount_paid + '.$amountFull),
                    'amount_due_remaining' => 0,
                    'updated_at'           => now(),
                ]);
                return redirect($nextUrl)->with('msg', 'Betaling voltooid (fake).');
            }

            if ($betaalOptie === 1) {
                // Simuleer 1/2 aanbetaling
                DB::table('aanmeldingen')->where('id', $aanmeldingId)->update([
                    'betaal_status'        => 1,
                    'amount_paid'          => DB::raw('amount_paid + '.$amountHalf),
                    'amount_due_remaining' => $amountHalf,
                    'due_at'               => $dueAt,
                    'updated_at'           => now(),
                ]);
                return redirect($nextUrl)->with('msg', 'Aanbetaling ontvangen (fake).');
            }
        }

        // ───────────── ECHTE STRIPE CHECKOUT ─────────────

        // Volledige betaling
        if ($betaalOptie === 2) {
            $session = $this->stripe->checkout->sessions->create([
                'mode'     => 'payment',
                'customer' => $customerId,
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                    'metadata' => [
                        'aanmelding_id' => (string) $aanmeldingId,
                        'betaal_optie'  => 'volledig',
                    ],
                ],
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency'    => 'eur',
                        'unit_amount' => $amountFull,
                        'product_data'=> ['name' => 'Training #'.$training->id.' - volledige betaling'],
                    ],
                ]],
                'success_url' => $successUrl,
                'cancel_url'  => $cancelUrl,
            ]);

            DB::table('aanmeldingen')->where('id', $aanmeldingId)->update([
                'stripe_checkout_session_id' => $session->id,
                'updated_at' => now(),
            ]);

            return redirect($session->url);
        }

        // Twee termijnen
        $session = $this->stripe->checkout->sessions->create([
            'mode'     => 'payment',
            'customer' => $customerId,
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session',
                'metadata' => [
                    'aanmelding_id' => (string) $aanmeldingId,
                    'betaal_optie'  => '2_termijnen',
                ],
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency'    => 'eur',
                    'unit_amount' => $amountHalf,
                    'product_data'=> ['name' => 'Training #'.$training->id.' - 1/2 aanbetaling'],
                ],
            ]],
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
        ]);

        DB::table('aanmeldingen')->where('id', $aanmeldingId)->update([
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

        $aanmelding = DB::table('aanmeldingen')
            ->where('stripe_checkout_session_id', $session->id)
            ->first();
        abort_unless($aanmelding, 404);

        if ($session->payment_status !== 'paid') {
            return redirect($next)->with('error', 'Betaling niet voltooid.');
        }

        $pi              = $session->payment_intent;
        $amountReceived  = (int) ($pi->amount_received ?? 0);
        $paymentMethodId = $pi->payment_method ?? null;

        $prijsEuro    = (float) Config::get('info.prijs');
        $totaalCents  = (int) round($prijsEuro * 100);
        $nieuweStatus = ($amountReceived >= $totaalCents) ? 2 : 1; // 2=volledig, 1=half

        DB::table('aanmeldingen')->where('id', $aanmelding->id)->update([
            'betaal_status'            => $nieuweStatus,
            'amount_paid'              => DB::raw('amount_paid + '.$amountReceived),
            'stripe_payment_intent_id' => $pi->id,
            'stripe_payment_method_id' => $paymentMethodId,
            'updated_at'               => now(),
        ]);

        return redirect($next)->with('msg', $nieuweStatus === 2 ? 'Betaling voltooid.' : 'Aanbetaling ontvangen.');
    }

    public function cancel()
    {
        $back = url()->previous() ?: url('/trainingen');
        return redirect($back)->with('error', 'Betaling geannuleerd.');
    }

    public function chargeRemaining($aanmeldingId){
        $a = DB::table('aanmeldingen')->where('id', $aanmeldingId)->first();
        abort_unless($a, 404);

        // Moet in half_paid status staan
        abort_if((int)$a->betaal_status !== 1, 400, 'Niet in half_paid status.');

        // Fake mode? Simuleer de resterende afschrijving en klaar
        $skipPayment = filter_var(env('PAYMENT_FAKE', false), FILTER_VALIDATE_BOOLEAN);
        if ($skipPayment) {
            DB::table('aanmeldingen')->where('id', $a->id)->update([
                'betaal_status'        => 2, // volledig betaald
                'amount_paid'          => DB::raw('amount_paid + '.(int)$a->amount_due_remaining),
                'amount_due_remaining' => 0,
                'updated_at'           => now(),
            ]);
            return back()->with('msg', 'Resterende betaling verwerkt (fake).');
        }

        // ---- Echte Stripe charge (alleen als PAYMENT_FAKE=false) ----
        abort_unless(!empty($a->stripe_customer_id) && !empty($a->stripe_payment_method_id), 400, 'Stripe IDs ontbreken.');

        $intent = $this->stripe->paymentIntents->create([
            'amount'         => (int) $a->amount_due_remaining,
            'currency'       => 'eur',
            'customer'       => $a->stripe_customer_id,
            'payment_method' => $a->stripe_payment_method_id,
            'off_session'    => true,
            'confirm'        => true,
            'metadata'       => [
                'aanmelding_id' => (string)$a->id,
                'betaal_optie'  => '2_termijnen_rest',
            ],
        ]);

        if ($intent->status === 'succeeded') {
            DB::table('aanmeldingen')->where('id', $a->id)->update([
                'betaal_status'        => 2,
                'amount_paid'          => DB::raw('amount_paid + '.$intent->amount),
                'amount_due_remaining' => 0,
                'updated_at'           => now(),
            ]);
            return back()->with('msg', 'Resterende betaling succesvol geïnd.');
        }

        if ($intent->status === 'requires_action') {
            return back()->with('error', 'Extra verificatie nodig. Stuur een betaallink voor de 2e termijn.');
        }

        return back()->with('error', 'Betaling mislukt: '.$intent->status);
    }

    private function getOrCreateCustomer(?string $email, ?string $name): string
    {
        if (!$email) {
            $c = $this->stripe->customers->create(['name' => $name ?: 'Onbekend']);
            return $c->id;
        }

        $existing = DB::table('aanmeldingen')
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
}
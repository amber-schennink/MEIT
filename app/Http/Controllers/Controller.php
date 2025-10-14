<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
  public function handleDeelnemerData($request){
    if($request->deelnemer_type){
      if($request->deelnemer_type == 'form'){
        $request->validate([
          'deelnemer_voornaam'                  => 'required|string',
          'deelnemer_achternaam'                => 'required|string',
          'deelnemer_email'                     => 'required|email',
          'deelnemer_wachtwoord'                => 'required|string|min:8|same:deelnemer_wachtwoord-bevestiging',
        ], [
          'deelnemer_wachtwoord.min'      => 'Het wachtwoord moet minstens :min tekens bevatten.',
          'deelnemer_wachtwoord.required' => 'Vul je wachtwoord in.',
          'deelnemer_wachtwoord.same'     => 'De wachtwoorden komen niet overeen.',
        ], [
          'deelnemer_voornaam'                   => 'voornaam',
          'deelnemer_achternaam'                 => 'achternaam',
          'deelnemer_email'                      => 'email',
          'deelnemer_wachtwoord'                 => 'wachtwoord',
          'deelnemer_wachtwoord-bevestiging'     => 'wachtwoord bevestiging',
        ]);
        $data_deelnemer = array(
          "voornaam" => $request->deelnemer_voornaam,
          "tussenvoegsel" => $request->deelnemer_tussenvoegsel,
          "achternaam" => $request->deelnemer_achternaam,
          "email" => $request->deelnemer_email,
          "telefoon_nummer" => $request->deelnemer_telefoon,
          "wachtwoord" => $request->deelnemer_wachtwoord
        );
        DB::table('deelnemers')->insert($data_deelnemer);
        $id_deelnemer = DB::getPdo()->lastInsertId();
        session(['login' => true, 'id' => $id_deelnemer, 'admin' => false]);

      }elseif($request->deelnemer_type == 'login'){
        $request->validate([
          'login_email'      => 'required|email',
          'login_wachtwoord' => 'required|string',
        ], [
          'login_wachtwoord.min'      => 'Het wachtwoord moet minstens :min tekens bevatten.',
          'login_wachtwoord.required' => 'Vul je wachtwoord in.',
          'login_wachtwoord.same'     => 'De wachtwoorden komen niet overeen.',
        ], [
          'login_email'                      => 'email',
          'login_wachtwoord'                 => 'wachtwoord',
        ]);
        $deelnemer = DB::table('deelnemers')
          ->where('email', '=', $request->login_email)
          ->first();
        $admin = DB::table('admins')
          ->where('email', '=', $request->login_email)
          ->first();
        if(!isset($deelnemer) && !isset($admin)){
          throw ValidationException::withMessages([
              'Verkeerde naam en wachtwoord combinatie',
            ])->redirectTo(url()->previous());
          die();
        }
        $plain = (string) $request->login_wachtwoord;

        if ($deelnemer) {
          $stored = (string) ($deelnemer->wachtwoord ?? '');
          $ok = false;

          if ($stored !== '' && str_starts_with($stored, '$')) {
            // Hash in DB → normaal checken
            $ok = Hash::check($plain, $stored);
          } else {
            // Legacy plaintext → direct vergelijken en daarna rehash opslaan
            if ($stored !== '' && hash_equals($stored, $plain)) {
              $ok = true;
              DB::table('deelnemers')->where('id', $deelnemer->id)->update([
                'wachtwoord' => Hash::make($plain),
                'updated_at' => now(),
              ]);
            }
          }

          if ($ok) {
            session(['login' => true, 'id' => $deelnemer->id, 'admin' => false]);
            $id_deelnemer = $deelnemer->id;
          }

        }elseif ($admin) {
          $stored = (string) ($admin->wachtwoord ?? '');
          $ok = false;

          if ($stored !== '' && str_starts_with($stored, '$')) {
            $ok = Hash::check($plain, $stored);
          } else {
            if ($stored !== '' && hash_equals($stored, $plain)) {
              $ok = true;
              DB::table('admins')->where('id', $admin->id)->update([
                'wachtwoord' => Hash::make($plain),
              ]);
            }
          }

          if ($ok) {
            session(['login' => true, 'id' => $admin->id, 'admin' => true]);
            return;
          }
        }
        
        if(!$ok){
          throw ValidationException::withMessages([
              'Verkeerde naam en wachtwoord combinatie',
            ])->redirectTo(url()->previous());
          die();
        }
      }
    }elseif($request->id_deelnemer){
      $id_deelnemer = $request->id_deelnemer;
    }
    return $id_deelnemer;
  }
}

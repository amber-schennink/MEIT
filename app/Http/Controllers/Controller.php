<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
  public function handleDeelnemerData($request){
    if($request->deelnemer_type){
      if($request->deelnemer_type == 'form'){
        $data_deelnemer = array(
          "voornaam" => $request->deelnemer_voornaam,
          "tussenvoegsel" => $request->deelnemer_tussenvoegsel,
          "achternaam" => $request->deelnemer_achternaam,
          "email" => $request->deelnemer_email,
          "wachtwoord" => $request->deelnemer_wachtwoord
        );
        DB::table('deelnemers')->insert($data_deelnemer);
        $id_deelnemer = DB::getPdo()->lastInsertId();
        session(['login' => true, 'id' => $id_deelnemer, 'admin' => false]);

      }elseif($request->deelnemer_type == 'login'){
        $deelnemer = DB::table('deelnemers')
          ->where('email', '=', $request->login_email)
          ->first();
        $admin = DB::table('admins')
          ->where('email', '=', $request->login_email)
          ->first();
        if(!isset($deelnemer) && !isset($admin)){
          return redirect()->back()->withErrors(['msg' => 'Verkeerde naam en wachtwoord combinatie']);
          die();
        }
        if(isset($deelnemer) && $deelnemer->wachtwoord == $request->login_wachtwoord){
          session(['login' => true, 'id' => $deelnemer->id, 'admin' => false]);
          $id_deelnemer = $deelnemer->id;
        }elseif(isset($admin) && $admin->wachtwoord == $request->login_wachtwoord){
          session(['login' => true, 'id' => $admin->id, 'admin' => true]);
          return;
        }else{
          return redirect()->back()->withErrors(['msg' => 'Verkeerde naam en wachtwoord combinatie']);
          die();
        }
      }
    }elseif($request->id_deelnemer){
      $id_deelnemer = $request->id_deelnemer;
    }
    return $id_deelnemer;
  }
}

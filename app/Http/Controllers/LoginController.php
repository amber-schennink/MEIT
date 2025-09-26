<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller {
  public function login(Request $request){
    $deelnemer = DB::table('deelnemers')
      ->where('email', '=', $request->email)
      ->first();
    $admin = DB::table('admins')
      ->where('email', '=', $request->email)
      ->first();
    if(!isset($deelnemer) && !isset($admin)){
      return redirect()->back()->withErrors(['msg' => 'Verkeerde naam en wachtwoord combinatie']);
      die();
    }
    if(isset($deelnemer) && $deelnemer->wachtwoord == $request->wachtwoord){
      session(['login' => true, 'id' => $deelnemer->id, 'admin' => false]);
      if(session('training')){
        $training = session('training');
        session(['training' => Null]);
        return Redirect::to('/aanmelden/'.$training);
      }else{
        return Redirect::to('/overzicht');
      }
    }elseif(isset($admin) && $admin->wachtwoord == $request->wachtwoord){
      session(['login' => true, 'id' => $admin->id, 'admin' => true]);
      return Redirect::to('/overzicht');
    }else{
      return redirect()->back()->withErrors(['msg' => 'Verkeerde naam en wachtwoord combinatie']);
      die();
    }
  }
}
?>
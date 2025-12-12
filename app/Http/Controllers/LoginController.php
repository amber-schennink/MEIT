<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller {
  public function login(Request $request){
    $this->handleDeelnemerData($request);
    return Redirect::to('/overzicht');
  }
  public function deelnemerVerwijderen($id){
    if(!session('login') && !session('id') && session('admin') !== true){
      die();
    }
    DB::table('deelnemers')->where([
      ['id', '=', $id],
    ])->delete();
    DB::table('aanmeldingen')->where([
      ['id_deelnemer', '=', $id],
    ])->delete();
    DB::table('ceremonies')->where([
      ['id_deelnemer', '=', $id],
    ])->delete();
    DB::table('intakegesprekken')->where([
      ['id_deelnemer', '=', $id],
    ])->delete();
    return Redirect::to('deelnemers');
  }
}
?>
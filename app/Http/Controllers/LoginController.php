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
}
?>
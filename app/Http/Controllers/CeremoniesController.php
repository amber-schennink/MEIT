<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CeremoniesController extends Controller {
  public function ceremonieNieuw(Request $request){
    $data_ceremonie = [
      "datum" => $request->datum,
    ];
    DB::table('ceremonies')->insert($data_ceremonie);
    return Redirect::to('ceremonies');
  }
  public function ceremonieDatumAanpassen($id, Request $request){
    if(!session('login') && session('admin') !== true){
      die();
    }
    if($request->first_name){
      die();
    }
    DB::table('ceremonies')->where([
        ['id', '=', $id]
      ])->update([
      'datum' => $request->datum,
    ]);
    return Redirect::to('ceremonies');
  }
  public function ceremonieDeelnemerBetaalStatusAanpassen($betaalStatus, Request $request){
    if(!session('login') && session('admin') !== true){
      die();
    }
    if(DB::table('ceremonies')->where('id', '=', $request->id_ceremonie)->first()){
      DB::table('ceremonies')->where([
        ['id', '=', $request->id_ceremonie]
      ])->update([
        'betaal_status' => $betaalStatus,
      ]);
    }
    return Redirect::to('ceremonies');
  }
  public function ceremonieVerwijderen($id){
    $ceremonie = DB::table('ceremonies')->where('id', '=', $id)->first();

    if($ceremonie == null){
      return Redirect::to('ceremonies');
      die();
    }
    
    DB::table('ceremonies')->delete($id);
    return Redirect::to('ceremonies');
  }
  public function ceremonieDeelnemerVerwijderen($id){
    $ceremonie = DB::table('ceremonies')->where('id', '=', $id)->first();

    if($ceremonie == null){
      return Redirect::to('ceremonies');
      die();
    }
    
    DB::table('ceremonies')->where([
        ['id', '=', $id]
      ])->update([
      'id_deelnemer' => NULL,
      'pending_deelnemer_id' => NULL,
      'betaal_status' => NULL,
      'updated_at' => now(),
      'amount_paid' => 0,
      'stripe_customer_id' => NULL,
      'stripe_checkout_session_id' => NULL,
      'stripe_payment_intent_id' => NULL,
      'stripe_payment_method_id' => NULL,
      'customer_email' => NULL,
    ]);
    return Redirect::to('ceremonies');
  }

  public function ceremonieAanmelden($id, Request $request){

  }
}
?>
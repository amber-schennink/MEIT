<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CeremoniesController extends Controller {
  public function ceremonieNieuw(Request $request){
    $data_ceremonie = array(
      "id_deelnemer" => $request->id_deelnemer,
      "datum" => $request->datum,
    );
    DB::table('ceremonies')->insert($data_ceremonie);
    
    DB::table('intakegespreken')->delete($request->id_intakegesprek);

    return Redirect::to('overzicht');
  }
}
?>
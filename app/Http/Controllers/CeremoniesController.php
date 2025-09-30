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
  public function gesprekMogenlijkheidNieuw(Request $request){
    $zelfde_datum = DB::table('intake_mogenlijkheden')->where('datum', '=', $request->datum)->first();
    if($zelfde_datum != null){
      return redirect()->back()->withErrors(['msg' => 'Er is al een intakegesprek mogenlijkheid ingepland op deze dag']);
      die();
    }
    $data_ceremonie = array(
      "datum" => $request->datum,
      "begin_tijd" => $request->begin_tijd,
      "eind_tijd" => $request->eind_tijd,
    );
    DB::table('intake_mogenlijkheden')->insert($data_ceremonie);
    return Redirect::to('ceremonies');
  }
}
?>
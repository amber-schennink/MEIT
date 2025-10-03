<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CeremoniesController extends Controller {
  public function ceremonieNieuw(Request $request){
    $data_ceremonie = array(
      "id_deelnemer" => $request->id_deelnemer,
      "datum" => $request->datum,
    );
    DB::table('ceremonies')->insert($data_ceremonie);
    
    DB::table('intakegesprekken')->delete($request->id_intakegesprek);

    return Redirect::to('overzicht');
  }
  public function intakegesprekNieuw(Request $request){
    $mogenlijkheid = DB::table('intake_mogenlijkheden')->where('id', '=', $request->id_mogenlijkheid)->first();
    $intakegesprekken = DB::table('intakegesprekken')->where('datum', '=', $request->datum)->get();

    $begin_tijd = new DateTime($mogenlijkheid->begin_tijd);
    [$begin_uur, $begin_min] = explode(':', $begin_tijd->format('H:i'));
    $eind_tijd = new DateTime($mogenlijkheid->eind_tijd);
    [$eind_uur, $eind_min] = explode(':', $eind_tijd->format('H:i'));
    $eind_uur = str_pad($eind_uur - 1, 2, '0', STR_PAD_LEFT);
    if(($begin_tijd->format('H:i') > $request->begin_tijd) || ($eind_uur . ":" . $eind_min < $request->begin_tijd) ){
      return redirect()->back()->withErrors(['msg' => 'Het is niet mogenlijk om voor dit moment een intake gesprek te plannen']);
      die();
    }

    foreach ($intakegesprekken as $gesprek) {
      $begin_gesprek = new DateTime($gesprek->begin_tijd);
      [$begin_gesprek_uur, $begin_gesprek_min] = explode(':', $begin_gesprek->format('H:i'));
      $begin_gesprek_uur = str_pad($begin_gesprek_uur - 1, 2, '0', STR_PAD_LEFT);

      $eind_gesprek = new DateTime($gesprek->eind_tijd);
      
      if(($begin_gesprek_uur . ":" . $begin_gesprek_min <= $request->begin_tijd) || ($eind_gesprek->format('H:i') >= $request->begin_tijd)){
        return redirect()->back()->withErrors(['msg' => 'Het is niet mogenlijk om voor dit moment een intake gesprek te plannen']);
        die();
      }
    }
    
    $id_deelnemer = $this->handleDeelnemerData($request);
    $request_begin_tijd = new DateTime($request->begin_tijd);
    $request_eind_tijd = new DateTime($request->begin_tijd);
    [$request_eind_uur, $request_eind_min] = explode(':', $request_eind_tijd->format('H:i'));
    $request_eind_uur = str_pad($request_eind_uur + 1, 2, '0', STR_PAD_LEFT);

    $request['eind_tijd'] = $request_eind_uur . ':' . $request_eind_min;
    $request_eind_tijd = new DateTime($request->begin_tijd);


    $begin_diff = $begin_tijd->diff($request_begin_tijd);
    if($begin_diff->h > 0){
      [$mogenlijkheid_uur, $mogenlijkheid_min] = explode(':', $mogenlijkheid->begin_tijd);
      
      $mogenlijkheid_uur = str_pad($mogenlijkheid_uur + $begin_diff->h, 2, '0', STR_PAD_LEFT);
      $mogenlijkheid_min = str_pad($mogenlijkheid_min + $begin_diff->i, 2, '0', STR_PAD_LEFT);
      $eind_tijd_mogenlijkheid = $mogenlijkheid_uur . ':' . $mogenlijkheid_min;

      $data_mogenlijkheid = [
        "datum" => $mogenlijkheid->datum,
        "begin_tijd" => $mogenlijkheid->begin_tijd,
        "eind_tijd" => $eind_tijd_mogenlijkheid,
      ];
      DB::table('intake_mogenlijkheden')->insert($data_mogenlijkheid);
    }

    $eind_diff = $eind_tijd->diff($request_eind_tijd);
    if($eind_diff->h > 0){
      [$mogenlijkheid_uur, $mogenlijkheid_min] = explode(':', $mogenlijkheid->eind_tijd);
      
      $mogenlijkheid_uur = str_pad($mogenlijkheid_uur - $eind_diff->h + 1, 2, '0', STR_PAD_LEFT);
      $mogenlijkheid_min = str_pad($mogenlijkheid_min - $eind_diff->i, 2, '0', STR_PAD_LEFT);
      $begin_tijd_mogenlijkheid = $mogenlijkheid_uur . ':' . $mogenlijkheid_min;

      $data_mogenlijkheid = [
        "datum" => $mogenlijkheid->datum,
        "begin_tijd" => $begin_tijd_mogenlijkheid,
        "eind_tijd" => $mogenlijkheid->eind_tijd,
      ];
      DB::table('intake_mogenlijkheden')->insert($data_mogenlijkheid);
    }
    DB::table('intake_mogenlijkheden')->delete($mogenlijkheid->id);


    $data_intakegesprekken = [
      "id_deelnemer" => $id_deelnemer,
      "datum" => $request->datum,
      "begin_tijd" => $request->begin_tijd,
      "eind_tijd" => $request->eind_tijd,
    ];
    

    DB::table('intakegesprekken')->insert($data_intakegesprekken);
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
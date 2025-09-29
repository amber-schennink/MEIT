<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use function PHPUnit\Framework\isEmpty;

class TrainingenController extends Controller {
  public function trainingNieuw(Request $request){
    if($request->first_name){
      die();
    }

    $data_training = array(
      "start_moment" => $request->dag_1 ." " . $request->begin_tijd_1,
      "start_moment_2" => $request->dag_2 ." " . $request->begin_tijd_2,
      "start_moment_3" => $request->dag_3 ." " . $request->begin_tijd_3,
      "start_moment_4" => $request->dag_4 ." " . $request->begin_tijd_4
    );
    DB::table('trainingen')->insert($data_training);

    return Redirect::to('trainingen');
  }
  public function trainingAanpassen($id, Request $request){
    if($request->first_name){
      die();
    }

    $data_training = array(
      "start_moment" => $request->dag_1 ." " . $request->begin_tijd_1,
      "start_moment_2" => $request->dag_2 ." " . $request->begin_tijd_2,
      "start_moment_3" => $request->dag_3 ." " . $request->begin_tijd_3,
      "start_moment_4" => $request->dag_4 ." " . $request->begin_tijd_4
    );
    DB::table('trainingen')->where('id', '=', $id)->update($data_training);
    

    return Redirect::to('training/'.$id);
  }
  public function trainingVerwijderen($id){
    $training = DB::table('trainingen')->where('id', '=', $id)->first();

    $aanmeldingen = DB::table('aanmeldingen')->where('id_training', '=', $id)->get();

    if($training == null){
      return Redirect::to('trainingen');
      die();
    }

    foreach ($aanmeldingen as $aanmelding) {
      DB::table('aanmeldingen')->delete($aanmelding->id);
    }
    
    DB::table('trainingen')->delete($id);
    return Redirect::to('trainingen');
  }
}


?>
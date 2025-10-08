<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;

class AanmeldingenController extends Controller {
  public function nieuweAanmelding(Request $request){
    if($request->first_name){
      die();
    }
    if(session('admin') == true){
      return Redirect::to('overzicht');
      die();
    }

    if($request->id_deelnemer){
      $id_deelnemer = $request->id_deelnemer;
    }else{
      $data_deelnemer = array(
        "voornaam" => $request->voornaam,
        "tussenvoegsel" => $request->tussenvoegsel,
        "achternaam" => $request->achternaam,
        "email" => $request->email,
        "wachtwoord" => $request->wachtwoord
      );
      DB::table('deelnemers')->insert($data_deelnemer);
      $id_deelnemer = DB::getPdo()->lastInsertId();
      session(['login' => true, 'id' => $id_deelnemer, 'admin' => false]);
    }
    

    $data_aanmelding = array(
      "id_deelnemer" => $id_deelnemer,
      "id_training" => $request->id_training,
      "betaal_status" => $request->betaal_optie
    );

    $aanmelding = DB::table('aanmeldingen')
      ->where([['id_deelnemer', '=', $id_deelnemer], ['id_training', '=', $request->id_training]])
      ->first();

    if($aanmelding != null){
      $aanmelding = DB::table('aanmeldingen')
        ->where([['id_deelnemer', '=', $id_deelnemer], ['id_training', '=', $request->id_training]])
        ->update($data_aanmelding);
    }else{
      DB::table('aanmeldingen')->insert($data_aanmelding);
    }

    return Redirect::to('overzicht');
  }
  public function afmelden($id_training){
    if(!session('login') && !session('id')){
      die();
    }
    DB::table('aanmeldingen')->where([
      ['id_training', '=', $id_training],
      ['id_deelnemer', '=', session('id')]
    ])->delete();
    return Redirect::to('overzicht');
  }
  public function export(){
    $headers = [
      "Content-type" => "text/csv",
      "Content-Disposition" => "attachment; filename=file.csv",
      "Pragma" => "no-cache",
      "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
      "Expires" => "0"
    ];

    $aanmeldingen = DB::table('aanmeldingen')->get();
    $trainingen = DB::table('trainingen')->orderBy('id','desc')->get();
    $deelnemers = DB::table('deelnemers')->get();
    $columns = ['Voornaam', 'Tussenvoegsel', 'Achternaam', 'Email', 'Betaal status'];

    $callback = function() use ($aanmeldingen, $trainingen, $deelnemers, $columns){
      $maanden = Config::get('info.maanden');
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns, ";");

      foreach($deelnemers as $deelnemer) {
        fputcsv($file, [$deelnemer->voornaam, $deelnemer->tussenvoegsel, $deelnemer->achternaam, $deelnemer->email], ";");
        fputcsv($file, [], ";");
        
        $aanmelding_deelnemer = $aanmeldingen->where('id_deelnemer', '=', $deelnemer->id);
        foreach ($aanmelding_deelnemer as $aanmelding) {
          $training = $trainingen->where('id', '=', $aanmelding->id_training)->first();
          if($training == null){
            continue;
          }
          $start = new DateTime($training->start_moment);
          $maand = $start->format('m') - 1;
          $deadline = $start->modify('-7 day');
          $deadline_maand = $deadline->format('m') - 1;
          $line = ['Training ' . $start->format('j') . ' ' . $maanden[$maand]];
          if($aanmelding->betaal_status == 2){
            $line[] = 'Betaald';
          }elseif($aanmelding->betaal_status == 1){
            $line[] = '1 termijn betaald';
            $line[] = 'de deadline voor het 2de termijn is ' . $deadline->format('j') . ' ' . $maanden[$deadline_maand];
          }else{
            $line[] = 'Staat op wachtlijst';
          }
          fputcsv($file, $line, ";");
        }
        fputcsv($file, [], ";");
        fputcsv($file, [], ";");
      }
      fclose($file);
    };
    return response()->stream($callback, 200, $headers);
  }
}

?>
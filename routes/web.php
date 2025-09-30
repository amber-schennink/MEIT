<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('home');
});

Route::get('/training/{id}', function ($id) {
  $training = DB::table('trainingen')->where('id', '=', $id)->first(); 
  return view('training', ['training' => $training]);
});

Route::get('/trainingen', function () {
  if(session('admin') == true){
    $trainingen = DB::table('trainingen')->orderBy('id','desc')->get();
    $aanmeldingen = DB::table('aanmeldingen')->orderBy('id','desc')->get();
    $deelnemers = DB::table('deelnemers')->get();

    $ceremonies = DB::table('ceremonies')->get();
    $intakegespreken = DB::table('intakegespreken')->get();

    return view('overzicht_trainingen', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemers' => $deelnemers, 
      'ceremonies' => $ceremonies, 'intakegespreken' => $intakegespreken
    ]);

  }else{
    $trainingen = DB::table('trainingen')->orderBy('id','desc')->get(); 
    return view('trainingen', ['trainingen' => $trainingen]);
  }
});

Route::get('/training_form', function () {
  return view('training_form');
});

Route::get('/training_form/{id}', function ($id) {
  $training = DB::table('trainingen')->where('id', '=', $id)->first(); 
  return view('training_form', ['training' => $training]);
});

Route::post('/training', 'App\Http\Controllers\TrainingenController@trainingNieuw');
Route::post('/training/{id}', 'App\Http\Controllers\TrainingenController@trainingAanpassen');

Route::get('/training_verwijderen/{id}', 'App\Http\Controllers\TrainingenController@trainingVerwijderen');

Route::get('/aanmelden/{id}', function ($id) {
  $wachtlijst = false;
  if(session('login') && session('id')){
    $wachtlijst = DB::table('aanmeldingen')
      ->where([
        ['id_deelnemer', '=', session('id')],
        ['id_training', '=', $id],
        ['betaal_status', '=', 0]
      ])
      ->exists();
  }
  $beschikbaar = 4 - DB::table('aanmeldingen')->where([
    ['id_training', '=', $id],
    ['betaal_status', '!=', 0]
    ])->count();
  $training = DB::table('trainingen')->where('id', '=', $id)->first(); 
  return view('aanmelden', ['training' => $training, 'wachtlijst' => $wachtlijst, 'beschikbaar' => $beschikbaar]);
});

Route::post('/aanmelden', 'App\Http\Controllers\AanmeldingenController@nieuweAanmelding');

Route::get('/afmelden/{id_training}', 'App\Http\Controllers\AanmeldingenController@afmelden');

Route::get('/overzicht', function () {
  if(!session('login') || !session('id')){
    return redirect(url('/login'));
  }
  if(session('admin')){
    $trainingen = DB::table('trainingen')->orderBy('id','desc')->get();
    $aanmeldingen = DB::table('aanmeldingen')->orderBy('id','desc')->get();
    $deelnemers = DB::table('deelnemers')->get();

    $ceremonies = DB::table('ceremonies')->get();
    $intakegespreken = DB::table('intakegespreken')->get();
    $intake_mogenlijkheden = DB::table('intake_mogenlijkheden')->get();

    return view('overzicht', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemers' => $deelnemers, 
      'ceremonies' => $ceremonies, 'intakegespreken' => $intakegespreken, 'intake_mogenlijkheden' => $intake_mogenlijkheden
    ]);
  }else{
    $deelnemer = DB::table('deelnemers')->where('id', '=', session('id'))->first();
    $aanmeldingen = DB::table('aanmeldingen')->get();
    $ids = [];
    $betaal_statuses = [];
    $beschikbaar = [];
    $ids_wachtlijst = [];
    foreach($aanmeldingen as $val){
      if(!isset($beschikbaar[$val->id_training])){
        $beschikbaar[$val->id_training] = 4;
      }
      if($val->betaal_status != 0){
        $beschikbaar[$val->id_training]--;
      }
      if($val->id_deelnemer == session('id')){
        if($val->betaal_status == 0){
          $ids_wachtlijst[] = $val->id_training;
        }else{
          $ids[] = $val->id_training;
          $betaal_statuses[$val->id_training] = $val->betaal_status;
        }
      }
    }
    $trainingen = DB::table('trainingen')->whereIn('id', $ids)->orderBy('id','desc')->get();
    $wachtlijst = DB::table('trainingen')->whereIn('id', $ids_wachtlijst)->orderBy('id','desc')->get();
    
    $ceremonies = DB::table('ceremonies')->where('id_deelnemer', '=', session('id'))->get();
    $intakegespreken = DB::table('intakegespreken')->where('id_deelnemer', '=', session('id'))->get();

    return view('overzicht_deelnemers', [
      'trainingen' => $trainingen, 'betaal_statuses' => $betaal_statuses, 'deelnemer' => $deelnemer, 'wachtlijst' => $wachtlijst, 'beschikbaar' => $beschikbaar, 
      'ceremonies' => $ceremonies, 'intakegespreken' => $intakegespreken
    ]);
  }
});
Route::get('/overzicht_export', 'App\Http\Controllers\AanmeldingenController@export');

Route::get('/ceremonies/{id_intakegesprek}', function ($id_intakegesprek) {
  $intakegesprek = DB::table('intakegespreken')->where('id', '=', $id_intakegesprek)->first();
  $deelnemer = DB::table('deelnemers')->where('id', '=', $intakegesprek->id_deelnemer)->first();
  return view('ceremonie_form', ['intakegesprek' => $intakegesprek, 'deelnemer' => $deelnemer]);
});

Route::post('/ceremonies', 'App\Http\Controllers\CeremoniesController@ceremonieNieuw');

Route::post('/gesprek_mogenlijkheden', 'App\Http\Controllers\CeremoniesController@gesprekMogenlijkheidNieuw');

Route::get('/login', function () {
  return view('login');
});
Route::get('/logout', function () {
  return view('logout');
});

Route::post('/login', 'App\Http\Controllers\loginController@login');
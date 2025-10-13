<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
  return view('home');
});

Route::get('/training/{id}', function ($id) {
  $training = DB::table('trainingen')->where('id', '=', $id)->first(); 
  return view('training', ['training' => $training]);
});

Route::get('/trainingen', function () {
  if(session('admin') == true){
    $trainingen = DB::table('trainingen')->orderByDesc('start_moment')->get();
    $aanmeldingen = DB::table('aanmeldingen')->orderByDesc('created_at')->get();
    $deelnemers = DB::table('deelnemers')->get();

    $ceremonies = DB::table('ceremonies')->get();
    $intakegesprekken = DB::table('intakegesprekken')->get();

    return view('overzicht_trainingen', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemers' => $deelnemers, 
      'ceremonies' => $ceremonies, 'intakegesprekken' => $intakegesprekken
    ]);

  }else{
    $trainingen = DB::table('trainingen')->orderBy('start_moment')->get(); 
    return view('trainingen', ['trainingen' => $trainingen]);
  }
});

Route::get('/training_form', function () {
  if(!session('login') || !session('id') || !session('admin')){
    return redirect(url('/login'));
  }
  if(session('admin') == true){
    return view('training_form');
  }
});

Route::get('/training_form/{id}', function ($id) {
  if(!session('login') || !session('id') || !session('admin')){
    return redirect(url('/login'));
  }
  if(session('admin') == true){
    $training = DB::table('trainingen')->where('id', '=', $id)->first(); 
    return view('training_form', ['training' => $training]);
  }
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

Route::post('/aanmelden', [CheckoutController::class, 'start'])->name('checkout.start');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel',  [CheckoutController::class, 'cancel'])->name('checkout.cancel');

// Alleen nodig voor 2-termijnen (de 2e helft later afschrijven, off_session):
Route::get('/checkout/charge-remaining/{aanmelding}', [CheckoutController::class, 'chargeRemaining'])->name('checkout.charge_remaining');

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
    $intakegesprekken = DB::table('intakegesprekken')->get();
    $intake_mogelijkheden = DB::table('intake_mogelijkheden')->get();

    return view('overzicht', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemers' => $deelnemers, 
      'ceremonies' => $ceremonies, 'intakegesprekken' => $intakegesprekken, 'intake_mogelijkheden' => $intake_mogelijkheden
    ]);
  }else{
    $deelnemer = DB::table('deelnemers')->where('id', '=', session('id'))->first();
    $aanmeldingen = DB::table('aanmeldingen')->where('betaal_status', '!=', '0')->get();
    $aanmeldingen_deelnemer = DB::table('aanmeldingen')->where([['id_deelnemer', '=', session('id')], ['betaal_status', '!=', '0']])->pluck('id_training');
    $aanmeldingen_wachtlijst = DB::table('aanmeldingen')->where([['id_deelnemer', '=', session('id')], ['betaal_status', '=', '0']])->pluck('id_training');
    
    $trainingen = DB::table('trainingen')->whereIn('id', $aanmeldingen_deelnemer)->orderBy('id','desc')->get();
    $wachtlijst = DB::table('trainingen')->whereIn('id', $aanmeldingen_wachtlijst)->orderBy('id','desc')->get();
    
    $ceremonies = DB::table('ceremonies')->where('id_deelnemer', '=', session('id'))->get();
    $intakegesprekken = DB::table('intakegesprekken')->where('id_deelnemer', '=', session('id'))->get();

    return view('overzicht_deelnemers', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemer' => $deelnemer, 'wachtlijst' => $wachtlijst, 
      'ceremonies' => $ceremonies, 'intakegesprekken' => $intakegesprekken
    ]);
  }
});
Route::get('/overzicht_export', 'App\Http\Controllers\AanmeldingenController@export');

Route::get('/ceremonies/{id_intakegesprek}', function ($id_intakegesprek) {
  $intakegesprek = DB::table('intakegesprekken')->where('id', '=', $id_intakegesprek)->first();
  $deelnemer = DB::table('deelnemers')->where('id', '=', $intakegesprek->id_deelnemer)->first();
  return view('ceremonie_form', ['intakegesprek' => $intakegesprek, 'deelnemer' => $deelnemer]);
});

Route::get('ceremonies', function (){
  if(session('admin') == true){
    $deelnemers = DB::table('deelnemers')->get();
    $ceremonies = DB::table('ceremonies')->orderBy('datum')->get();
    $intakegesprekken = DB::table('intakegesprekken')->orderBy('datum')->orderBy('begin_tijd')->get();
    $intake_mogelijkheden = DB::table('intake_mogelijkheden')->get();

    return view('overzicht_ceremonies', [
      'deelnemers' => $deelnemers, 'ceremonies' => $ceremonies, 
      'intakegesprekken' => $intakegesprekken, 'intake_mogelijkheden' => $intake_mogelijkheden
    ]);
  }else{
    $intakegesprekken = DB::table('intakegesprekken')->get();
    $intake_mogelijkheden = DB::table('intake_mogelijkheden')->get();
    return view('ceremonies', ['intakegesprekken' => $intakegesprekken, 'intake_mogelijkheden' => $intake_mogelijkheden]);
  }
});

Route::post('/ceremonies', 'App\Http\Controllers\CeremoniesController@ceremonieNieuw');

Route::post('/intakegesprek', 'App\Http\Controllers\CeremoniesController@intakegesprekNieuw');
Route::post('/gesprek_mogelijkheden', 'App\Http\Controllers\CeremoniesController@gesprekMogenlijkheidNieuw');

Route::get('/login', function () {
  return view('login');
});
Route::get('/logout', function () {
  return view('logout');
});

Route::post('/login', 'App\Http\Controllers\loginController@login');
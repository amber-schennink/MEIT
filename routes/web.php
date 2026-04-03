<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CeremonieCheckoutController;

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

    return view('overzicht_trainingen', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemers' => $deelnemers
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

Route::get('/algemene_voorwaarden_traject', function () {
  return view('algemene_voorwaarden_traject');
});

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

Route::post('/aanmeldingDeelnemerAanpassen/{id}', 'App\Http\Controllers\AanmeldingenController@aanpassenDeelnemerAanmelding');

Route::get('/afmeldenDeelnemer/{id_aanmelding}', 'App\Http\Controllers\AanmeldingenController@deelnemerAfmelden');

Route::get('/afmelden/{id_training}', 'App\Http\Controllers\AanmeldingenController@afmelden');

Route::get('/deelnemer_verwijderen/{id}', 'App\Http\Controllers\LoginController@deelnemerVerwijderen');

Route::get('/overzicht', function () {
  if(!session('login') || !session('id')){
    return redirect(url('/login'));
  }
  if(session('admin')){
    $trainingen = DB::table('trainingen')->orderBy('id','desc')->get();
    $aanmeldingen = DB::table('aanmeldingen')->orderBy('id','desc')->get();
    $deelnemers = DB::table('deelnemers')->get();

    $ceremonies = DB::table('ceremonies')->get();

    return view('overzicht', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemers' => $deelnemers, 
      'ceremonies' => $ceremonies
    ]);
  }else{
    $deelnemer = DB::table('deelnemers')->where('id', '=', session('id'))->first();
    $aanmeldingen = DB::table('aanmeldingen')->where('betaal_status', '!=', '0')->get();
    $aanmeldingen_deelnemer = DB::table('aanmeldingen')->where([['id_deelnemer', '=', session('id')], ['betaal_status', '!=', '0']])->pluck('id_training');
    $aanmeldingen_wachtlijst = DB::table('aanmeldingen')->where([['id_deelnemer', '=', session('id')], ['betaal_status', '=', '0']])->pluck('id_training');
    
    $trainingen = DB::table('trainingen')->whereIn('id', $aanmeldingen_deelnemer)->orderBy('id','desc')->get();
    $wachtlijst = DB::table('trainingen')->whereIn('id', $aanmeldingen_wachtlijst)->orderBy('id','desc')->get();
    
    $ceremonies = DB::table('ceremonies')->where('id_deelnemer', '=', session('id'))->orderBy('datum')->get();

    return view('overzicht_deelnemers', [
      'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemer' => $deelnemer, 'wachtlijst' => $wachtlijst, 
      'ceremonies' => $ceremonies
    ]);
  }
});
Route::get('/overzicht_export', 'App\Http\Controllers\AanmeldingenController@export');
Route::get('/deelnemers', function () {
  if(!session('login') || !session('id') || session('admin') !== true){
    return redirect(url('/login'));
  }
  $deelnemers = DB::table('deelnemers')->orderBy('id','desc')->get();
  $aanmeldingen = DB::table('aanmeldingen')->where('betaal_status', '!=', '0')->get();
  $wachtlijst = DB::table('aanmeldingen')->where('betaal_status', '=', '0')->get();
  $ceremonies = DB::table('ceremonies')->get();

  return view('deelnemers', [
      'deelnemers' => $deelnemers, 'aanmeldingen' => $aanmeldingen, 'wachtlijst' => $wachtlijst,
      'ceremonies' => $ceremonies
    ]);
});
Route::get('/deelnemers/{id}', function ($id) {
  if(!session('login') || !session('id') || session('admin') !== true){
    return redirect(url('/login'));
  }
  $deelnemer = DB::table('deelnemers')->where('id', '=', $id)->first();
  $aanmeldingen = DB::table('aanmeldingen')->where('betaal_status', '!=', '0')->get();

  $aanmeldingen_deelnemer = DB::table('aanmeldingen')->where([['id_deelnemer', '=', $id], ['betaal_status', '!=', '0']])->pluck('id_training');
  $aanmeldingen_wachtlijst = DB::table('aanmeldingen')->where([['id_deelnemer', '=', $id], ['betaal_status', '=', '0']])->pluck('id_training');
  
  $trainingen = DB::table('trainingen')->whereIn('id', $aanmeldingen_deelnemer)->orderBy('id','desc')->get();
  $wachtlijst = DB::table('trainingen')->whereIn('id', $aanmeldingen_wachtlijst)->orderBy('id','desc')->get();

  $ceremonies = DB::table('ceremonies')->where('id_deelnemer', '=', $id)->orderBy('datum')->get();

  return view('overzicht_deelnemers', [
      'id' => $id, 'admin' => true, 'trainingen' => $trainingen, 'aanmeldingen' => $aanmeldingen, 'deelnemer' => $deelnemer, 'wachtlijst' => $wachtlijst, 
      'ceremonies' => $ceremonies,
    ]);
});

Route::get('/ceremonie_form', function () {
  // $ceremonies = DB::table('ceremonies')->orderBy('datum')->get();
  return view('ceremonie_form'); //, ['ceremonies' => $ceremonies]
});
Route::get('/ceremonie_form/{id}', function ($id) {
  if(!session('login') || !session('id') || !session('admin')){
    return redirect(url('/login'));
  }
  if(session('admin') == true){
    $ceremonie = DB::table('ceremonies')->where('id', '=', $id)->first(); 
    return view('ceremonie_form', ['ceremonie' => $ceremonie]);
  }
});

Route::post('/ceremonie_aanmelden', [CeremonieCheckoutController::class, 'start'])->name('ceremonie_checkout.start');
Route::get('/ceremonie_checkout/success', [CeremonieCheckoutController::class, 'success'])->name('ceremonie_checkout.success');
Route::get('/ceremonie_checkout/cancel',  [CeremonieCheckoutController::class, 'cancel'])->name('ceremonie_checkout.cancel');
Route::post('/ceremonie-checkout/abandon', [CeremonieCheckoutController::class, 'abandon'])->name('ceremonie_checkout.abandon');
Route::post('/stripe/webhook', [CeremonieCheckoutController::class, 'webhook'])->name('stripe.webhook');

Route::post('/ceremonieDatumAanpassen/{id}', 'App\Http\Controllers\CeremoniesController@ceremonieDatumAanpassen');
Route::post('/ceremonieDeelnemerBetaalStatusAanpassen/{betaalStatus}', 'App\Http\Controllers\CeremoniesController@ceremonieDeelnemerBetaalStatusAanpassen');
Route::get('/ceremonie_verwijderen/{id}', 'App\Http\Controllers\CeremoniesController@ceremonieVerwijderen');
Route::get('/ceremonie_deelnemer_verwijderen/{id}', 'App\Http\Controllers\CeremoniesController@ceremonieDeelnemerVerwijderen');
Route::post('/ceremonie_deelnemer_toevoegen/{id}', 'App\Http\Controllers\CeremoniesController@ceremonieDeelnemerToevoegen');

Route::get('ceremonies', function (){
  if(session('admin') == true){
    $deelnemers = DB::table('deelnemers')->get();
    $ceremonies = DB::table('ceremonies')->orderBy('datum')->get();

    return view('overzicht_ceremonies', [
      'deelnemers' => $deelnemers, 'ceremonies' => $ceremonies
    ]);
  }else{
    $deelnemers = DB::table('deelnemers')->get();
    $ceremonies = DB::table('ceremonies')->orderBy('datum')->get(); //->whereNull('id_deelnemer')
    return view('ceremonies', [
      'deelnemers' => $deelnemers, 'ceremonies' => $ceremonies
      ]);
  }
});

Route::get('/ceremonie/{id}', function ($id) {
  $ceremonie = DB::table('ceremonies')->where('id', '=', $id)->first(); 
  return view('ceremonie', ['ceremonie' => $ceremonie]);
});

Route::post('/ceremonies', 'App\Http\Controllers\CeremoniesController@ceremonieNieuw');

Route::get('/login', function () {
  return view('login');
});
Route::get('/logout', function () {
  return view('logout');
});

Route::post('/login', 'App\Http\Controllers\LoginController@login');
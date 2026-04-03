<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
  <body class="bg-main">
    @include('partials.nav')
    @include('partials.flash')
    <?php
      use Illuminate\Support\Facades\DB; 

      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');
      $weekDagen = Config::get('info.weekDagen');
      $prijs = Config::get('info.prijs');

      $deadline = New DateTime();
      $deadline->modify('+5 days');

      if(!$ceremonie || $ceremonie->id_deelnemer || $deadline->format('Y-m-d') > $ceremonie->datum){
        redirect(url('/ceremonies'));
        die();
      }
    ?>
    
    <div class="container">
      <h2>Aanmelden ceremonie</h2>
      <br>
      <!-- <form onsubmit="return checkForm()" action="" method="POST">  -->
      <form onsubmit="return checkForm()" action="{{ route('ceremonie_checkout.start') }}" method="POST">
        @csrf
        <input name="first_name" type="text" class="hidden">
        <input name="id_ceremonie" value="{{$ceremonie->id}}" class="hidden"/>
        @if(session('login') && session('id'))
          <?php 
            $deelnemer = DB::table('deelnemers')->where('id', '=', session('id'))->first();
          ?>
          <input id="id_deelnemer" name="id_deelnemer" value="{{session('id')}}" class="hidden"/>
          <h3>Info deelnemer</h3>
          <div class="my-8 font-semibold flex flex-col">
            <p>Naam</p>
            <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
            <p class="mt-4">E-mail</p>
            <p>{{$deelnemer->email}}</p>
          </div>
        @else
          @include('partials.form_info_deelnemer')
        @endif
        <h3>Praktische info</h3>
        <div>
          <?php
            $datetime = new DateTime($ceremonie->datum);
          ?>
          <div class="my-8">
            <div class="flex my-3 items-center">
              <img class="h-7 mr-1" src="{{asset('assets/date.svg')}}" /> 
              <p>{{$weekDagen[$datetime->format('w')]}} {{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}}</p>
            </div>
            <div class="flex my-3 items-center">
              <img class="h-7 mr-1" src="{{asset('assets/time.svg')}}" /> 
              <p>11:00 - 16:00</p>
            </div>
            <div class="flex my-3 items-center">
              <img class="h-7 mr-1" src="{{asset('assets/location.svg')}}" /> 
              <p>Schiedam (Het Magische Huisje)</p>
            </div>
          </div>
        </div>
        @if(!$ceremonie->id_deelnemer)
        <div>
          <h3>Kies je betalingsoptie</h3>
          <div class="betaal-opties">
            <label class="bg-main-payed border-main-payed flex flex-col">
              <h4>Betaal het volledige bedrag</h4>
              <h4 class="text-xl ml-auto mt-auto !mb-0 font-bold w-fit">€{{$prijs}},-</h4>
              <input type="radio" name="betaal_optie" value="2" checked/>
            </label>
            <label class="bg-main-payed border-main-payed flex flex-col">
              <h4>Helft contant</h4>
              <p>Betaal €{{$prijs / 2}},- aan en voldoe de overige €{{$prijs / 2}},- contant op de dag van jouw ceremonie</p>
              <h4 class="text-xl ml-auto mt-auto !mb-0 font-bold w-fit">€{{$prijs / 2}},-</h4>
              <input type="radio" name="betaal_optie" value="0"/>
            </label>
          </div>
          <p>Voor beide betalingsopties kun je kiezen voor Klarna, waarmee je het bedrag in drie delen kunt betalen. Na je betaling is jouw plek bevestigd en neem ik snel contact met je op voor verdere informatie.</p>
          <br>
          <p>Tot snel ♡</p>
        </div>
        @endif
        <br>
        <label class="flex gap-2 checkbox-label">
          <input class="w-6 h-6 opacity-0 absolute" type="checkbox" required/>
          <span></span>
          <p>Ik heb de <a target="_blank" href="{{url('/algemene_voorwaarden_traject')}}" class="underline underline-offset-2">Algemene voorwaarden</a> gelezen en begrepen.</p>
        </label>
        <br>
        <button type="submit">Aanmelden</button>
      </form>
    </div>

    @include('partials.footer')
  </body>
</html>

@if(session('pending_ceremonie_aanmelding_id'))
  <div
    id="ceremonie-checkout-data"
    data-abandon-url="{{ route('ceremonie_checkout.abandon') }}"
    data-csrf-token="{{ csrf_token() }}"
  ></div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const checkoutData = document.getElementById('ceremonie-checkout-data');
    if (!checkoutData) return;

    const abandonUrl = checkoutData.dataset.abandonUrl;
    const csrfToken = checkoutData.dataset.csrfToken;

    window.addEventListener('pageshow', function (event) {
      const navEntries = performance.getEntriesByType('navigation');
      const nav = navEntries.length ? navEntries[0] : null;
      const isBackForward = event.persisted || (nav && nav.type === 'back_forward');

      if (!isBackForward) return;

      fetch(abandonUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).catch(() => {});
    });
  });
</script>
@endif

<script>
  function removeBorder(){
    ww.style.border = ''; 
    wwb.style.border = ''
  }
  function checkForm(){
    if(document.getElementById('id_deelnemer') == null){
      var ww = document.getElementById('ww')
      var wwb = document.getElementById('wwb')
      if(ww.value != wwb.value){
        ww.style = "border: 2px solid red;";
        wwb.style = "border: 2px solid red;";
        ww.focus();
        ww.addEventListener('input', removeBorder);
        ww.addEventListener('propertychange', removeBorder);
        wwb.addEventListener('input', removeBorder);
        wwb.addEventListener('propertychange', removeBorder);
        return false;
      }
    }
    var inputs = document.getElementsByTagName('input');
    radio_checked = false;
    for (let i = 0; i < inputs.length; i++) {
      if(inputs[i].type == 'radio' && inputs[i].checked){
        radio_checked = true
        break
      }
    }
    
    if(!radio_checked){
      return false;
    }
  }
</script>

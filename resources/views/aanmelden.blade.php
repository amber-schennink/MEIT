<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    @include('partials.flash')
    <?php
      use Illuminate\Support\Facades\DB; 

      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');
      $prijs = Config::get('info.prijs');
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <h2 class="mb-8">Aanmelden training</h2>
      <form onsubmit="return checkForm()" action="{{ route('checkout.start') }}" method="POST">
        @csrf
        <input name="first_name" type="text" class="hidden">
        <input name="id_training" value="{{$training->id}}" class="hidden"/>
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
          @include('form_info_deelnemer')
        @endif
        <h3>Info training</h3>
        <div class="training-overzicht">
          @foreach($training as $key => $val)
            @if(str_contains($key, 'start_moment'))
              <?php
                $datetime = new DateTime($val);
                $time = $datetime->format('H:i');
              ?>
              <div class="my-3">
                <p>{{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}}</p>
                <p>{{$time}} - {{date('H:i', strtotime($time) + 60*60*3)}}</p>
              </div>
            @endif
          @endforeach
        </div>
        <div>
          <h3>Kies je betaal optie</h3>
          <div class="betaal-opties">
            @if($beschikbaar > 0)
              <label class="bg-main-payed border-main-payed flex flex-col">
                <h4>Volledige betaling</h4>
                <h4 class="text-xl ml-auto mt-auto !mb-0 font-bold w-fit">€{{$prijs}},-</h4>
                <input type="radio" name="betaal_optie" value="2" checked/>
              </label>
              <label class="bg-main-light border-main-light">
                <h4>Betalen in 2 termijnen</h4>
                <?php 
                  $datetime = new DateTime($training->start_moment);
                  $datetime->modify('-7 day');
                ?>
                <p>Betaal nu €{{$prijs / 2}},- en €{{$prijs / 2}},- voor <span class="font-semibold">{{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}}</span></p>
                <h4 class="text-xl ml-auto mt-auto !mb-0 font-bold w-fit">2 x €{{$prijs / 2}},-</h4>
                <input type="radio" name="betaal_optie" value="1"/>
              </label>
            @endif
            @if(!$wachtlijst)
              <label class="bg-main-not-payed border-main-not-payed">
                <h4>Op wachtlijst zetten</h4>
                <p>Je bent niet gegarandeerd van een plek tot er een betaling is gedaan!</p>
                <input type="radio" name="betaal_optie" value="0" <?php if($beschikbaar <= 0){echo 'checked'; echo ' id="'.$beschikbaar.'"';} ?>/>
              </label>
            @endif
          </div>
        </div>
        <br>
        <br>
        @if($datetime < new DateTime())
          <p class="mb-3">Sorry het is niet meer mogenlijke om je aan te melden voor deze training</p>
          <button class="uit" type="button">Aanmelden</button>
        @else 
          <button type="submit">Aanmelden</button>
        @endif
      </form>
    </div>

  </body>
</html>
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

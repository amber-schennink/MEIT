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
      $prijs = Config::get('info.prijs');
    ?>
    
    <div class="container">
      <h2>Aanmelden traject</h2>
      <br>
      <div class="mb-8">
        <p>Wat goed dat je hier bent!</p>
        <br>
        <p>Hieronder vind je de vier data van jouw Traject. Kijk even goed in je agenda of je op alle dagen aanwezig kunt zijn, want dit traject vraagt om jouw volledige aanwezigheid en aandacht.</p>
        <br>
        <p>Belangrijk om te weten:</p>
        <div>
          <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/>Je plek is pas definitief na betaling</p>
          <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/>Pilotprijs: €333,- <br> Omdat dit traject nu voor het eerst van start gaat is dit een tijdelijke prijs. <br> Na de eerste paar trajecten wordt de vaste prijs €444,-</p>
          <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/>Annuleren kan kosteloos t/m 5 dagen vóór de startdatum</p>
          <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/>Na betaling ontvang je binnen 48 uur een bevestiging met alle praktische details.</p>
        </div>
        <br>
        <p>Voel je dat dit het juist moment is om jezelf beter te leren begrijpen? Dan heet ik je met alle <span class="text-second">liefde</span> welkom in het MEIT. Traject!</p>
        <br>
        <p>Twijfel je nog een beetje?</p>
        <p>Stuur gerust een mailtje naar <a href="mailto:welkom@meit.nl" class="hover:underline underline-offset-2 text-second">welkom@meit.nl</a>. Ik denk graag met je mee ♡</p>
      </div>
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
          @include('partials.form_info_deelnemer')
        @endif
        <h3>Praktische info</h3>
        <div class="training-overzicht">
          @foreach($training as $key => $val)
            @if(str_contains($key, 'start_moment'))
              <?php
                $datetime = new DateTime($val);
                $time = $datetime->format('H:i');
              ?>
              <div class="my-3">
                <div class="flex my-3 items-center">
                  <img class="h-7 mr-1" src="{{asset('assets/date.svg')}}" /> 
                  <p>{{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}}</p>
                </div>
                <div class="flex my-3 items-center">
                  <img class="h-7 mr-1" src="{{asset('assets/time.svg')}}" /> 
                  <p>{{$time}} - {{date('H:i', strtotime($time) + 60*60*3)}}</p>
                </div>
                <div class="flex my-3 items-center">
                  <img class="h-7 mr-1" src="{{asset('assets/location.svg')}}" /> 
                  <p>Schiedam (Het Magische Huisje)</p>
                </div>
              </div>
            @endif
          @endforeach
        </div>
        <div>
          <h3>Klaar om te starten?</h3>
          <div class="betaal-opties">
            @if($beschikbaar > 0)
              <label class="bg-main-payed border-main-payed flex flex-col">
                <h4>Eenmalige betaling</h4>
                <h4 class="text-xl ml-auto mt-auto !mb-0 font-bold w-fit">€{{$prijs}},-</h4>
                <input type="radio" name="betaal_optie" value="2" checked/>
              </label>
              <!-- 
              optie 2 termijnen
              
              <label class="bg-main-light border-main-light">
                <h4>Betalen in 2 termijnen</h4>
                <?php 
                  // $datetime = new DateTime($training->start_moment);
                  // $datetime->modify('-7 day');
                ?>
                <p>Betaal nu €{{$prijs / 2}},- en €{{$prijs / 2}},- voor <span class="font-semibold">{{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}}</span></p>
                <h4 class="text-xl ml-auto mt-auto !mb-0 font-bold w-fit">2 x €{{$prijs / 2}},-</h4>
                <input type="radio" name="betaal_optie" value="1"/>
              </label> -->
            @endif
            @if(!$wachtlijst && $beschikbaar == 0)
              <label class="bg-main-not-payed border-main-not-payed">
                <h4>Op wachtlijst zetten</h4>
                <p>Je bent niet gegarandeerd van een plek tot er een betaling is gedaan!</p>
                <input type="radio" name="betaal_optie" value="0" <?php if($beschikbaar <= 0){echo 'checked'; echo ' id="'.$beschikbaar.'"';} ?>/>
              </label>
            @endif
          </div>
          @if($beschikbaar > 0)
            <p class="text-sm -mt-5">Je deelname is definitief na betaling.</p>
          @endif
        </div>
        <br>
        <label class="flex gap-2 checkbox-label">
          <input class="w-6 h-6 opacity-0 absolute" type="checkbox" required/>
          <span></span>
          <p>Ik heb de <a target="_blank" href="{{url('/algemene_voorwaarden_traject')}}" class="underline underline-offset-2">Algemene voorwaarden</a> gelezen en begrepen.</p>
        </label>
        <br>
        <?php 
          $aanmeldingen = DB::table('aanmeldingen')
            ->where('id_training', '=', $training->id)->get();
          
          $btnTekst = 'Reserveer mijn plek';
          $extraTekst = '';
          $betaald = false;
          $btnUit = false;
          $termijn = false;
          $deelnemer = null;
          if(!isset($deadline)){
            $deadline = new DateTime('00:00:00');
            $deadline->modify('+5 days');
          }
          if(session('id')){
            $deelnemer = $aanmeldingen->where('id_deelnemer', '=',  session('id'))->first();
          }
          if($deelnemer){
            if($deelnemer->betaal_status == 1){
              $termijn = true;
            }elseif($deelnemer->betaal_status == 2){
              $btnTekst = 'Aangemeld';
              $btnUit = true;
            }
          }elseif(new DateTime($training->start_moment) < $deadline){
            $extraTekst = 'Sorry het is niet meer mogenlijke om je aan te melden voor dit traject';
            $btnUit = true;
          }elseif($beschikbaar == 0){
            $btnTekst = 'Opgeven wachtlijst';
          }
        ?>
        @if($extraTekst)
          <p class="mb-3">{{$extraTekst}}</p>
        @endif
        @if($btnUit)
          <button class="uit" type="button">{{$btnTekst}}</button>
        @elseif($termijn)
          <a href="{{url('/checkout/charge-remaining/' . $deelnemer->id)}}"><button type="button">Betaal termijn</button></a>
        @else 
          <button type="submit">{{$btnTekst}}</button>
        @endif
      </form>
    </div>

    @include('partials.footer')
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

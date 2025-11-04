<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    @include('partials.flash')

    <?php 
      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');
      $prijs = Config::get('info.prijs');
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      @if(isset($admin) && $admin === true)
        <h2>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</h2>
        <div>
          <a class="hover:underline underline-offset-2 flex items-center" href="mailto: {{$deelnemer->email}}">
            <img class="deelnemer-icons" src="{{asset('assets/email.svg')}}" /> 
            <p class="my-3">{{$deelnemer->email}}</p>
          </a>
        </div>
        @if(isset($deelnemer->telefoon_nummer))
          <div class="!mb-4">
            <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
              <img class="deelnemer-icons" src="{{asset('assets/telephone.svg')}}" /> 
              <p>{{$deelnemer->telefoon_nummer}}</p>
            </a>
          </div>
        @endif
        @if(isset($deelnemer->geboorte_datum) || isset($deelnemer->geboorte_tijd) || isset($deelnemer->geboorte_plaats))
          <p>Geboorte info</p>
        @endif
        @if(isset($deelnemer->geboorte_datum))
          <div class="flex items-center">
            <img class="deelnemer-icons" src="{{asset('assets/date.svg')}}" /> 
            <?php 
              $datetime = null;
              $datetime = new DateTime($deelnemer->geboorte_datum);
            ?>
            <p>{{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}} {{$datetime->format('Y')}}</p>
          </div>
        @endif
        @if(isset($deelnemer->geboorte_tijd))
          <div class="flex items-center">
            <img class="deelnemer-icons" src="{{asset('assets/time.svg')}}" /> 
            <?php 
              $datetime = null;
              $datetime = new DateTime($deelnemer->geboorte_tijd);
            ?>
            <p>{{$datetime->format('H:i')}}</p>
          </div>
        @endif
        @if(isset($deelnemer->geboorte_plaats))
          <div class="flex items-center">
            <img class="deelnemer-icons" src="{{asset('assets/location.svg')}}" /> 
            <p>{{$deelnemer->geboorte_plaats}}</p>
          </div>
        @endif
      @else
        <h2>Hallo {{$deelnemer->voornaam}}</h2>
      @endif
      <h3 id="trainingen" class="mt-5">Traject<?php if($trainingen->count() != 1){echo 'en';}?></h3>
        @if($trainingen->count() != 0) 
          <div class="trainingen">
            @foreach($trainingen as $training)
              <?php 
                if(isset($admin) && $admin){
                  $aanmelding = $aanmeldingen->where('id_training', '=', $training->id)->where('id_deelnemer', '=', $id)->first();
                }else{
                  $aanmelding = $aanmeldingen->where('id_training', '=', $training->id)->where('id_deelnemer', '=', session('id'))->first();
                }
                if($aanmelding->betaal_status == 2){
                  $betaald = true ;
                }else{
                  $betaald = false ;
                }
              ?>
              <div onclick="location.href = `{{url('training/'.$training->id)}}`" class="cursor-pointer flex flex-col justify-between gap-2
              <?php if(new DateTime($training->start_moment_4) < new DateTime('00:00:00')){echo 'opacity-75 ';} if($betaald) {echo ' !bg-main-payed';} ?>">
                <div class="datums">
                  @foreach($training as $key => $val)
                    @if(str_contains($key, 'start_moment'))
                      <?php
                        $datetime = new DateTime($val);
                        $maand = $datetime->format('m') - 1;
                      ?>
                      <div>
                        <p>{{$datetime->format('j')}}</p>
                        <p>{{substr($maanden[$maand], 0, 3)}}</p>
                      </div>
                    @endif
                  @endforeach
                </div>
                <p class="hover:underline underline-offset-2 mt-auto w-fit">Meer informatie -></p>
                @if($betaald)
                  <p class="font-bold text-2xl ml-auto">Betaald</p>
                @else
                  <?php 
                    $deadline = new DateTime($training->start_moment);
                    $deadline = $deadline->modify('-7 day');
                    $deadline_maand = $deadline->format('m') - 1;
                  ?>
                  <p>Eerste termijn van €{{$prijs / 2}},- betaald</p>
                  <p>Tweede temijn van €{{$prijs / 2}},- betalen voor <span class="font-semibold underline underline-offset-2">{{$deadline->format('j')}} {{$maanden[$deadline_maand]}}</span></p> 
                    
                  @if(!isset($admin) || $admin !== true)
                    @if($datetime < new DateTime('00:00:00'))
                      <h4 class="!text-xl">Sorry de deadline voor het betalen van het tweede termijn is verlopen</h4>
                      <button onclick="event.stopPropagation();" class="w-full uit">Betaal termijn</button>
                    @else
                      <a class="mt-3" onclick="event.stopPropagation();" href="{{url('/checkout/charge-remaining/' . $aanmelding->id)}}"><button class="w-full">Betaal termijn</button></a>
                    @endif
                  @endif
                @endif
              </div>
            @endforeach
          </div>
        @elseif($wachtlijst->isEmpty())
          @if(!isset($admin) || $admin !== true)
            <a href="{{url('trainingen')}}"><button class="mt-3">Meld je aan voor een traject!</button></a>
          @else 
            <p>Deze deelnemer heeft zich hiervoor nog niet aangemeld</p>
          @endif
        @endif
      @if($wachtlijst->isNotEmpty())
        <h5 id="wachtlijst" class="mt-5">Wachtlijst</h5>
        <div class="trainingen">
          @foreach($wachtlijst as $training)
            <div onclick="location.href = `{{url('training/'.$training->id)}}`" class="cursor-pointer flex flex-col justify-between !bg-main-not-payed <?php if(new DateTime($training->start_moment_4) < new DateTime('00:00:00')){echo 'opacity-75';} ?>">
              <div class="datums">
                @foreach($training as $key => $val)
                  @if(str_contains($key, 'start_moment'))
                    <?php
                      $datetime = new DateTime($val);
                      $maand = $datetime->format('m') - 1;
                    ?>
                    <div>
                      <p>{{$datetime->format('j')}}</p>
                      <p>{{substr($maanden[$maand], 0, 3)}}</p>
                    </div>
                  @endif
                @endforeach
              </div>
              <p class="hover:underline underline-offset-2 mt-auto w-fit">Meer informatie -></p>
              @if(!isset($admin) || $admin !== true)
                <?php 
                  $beschikbaar = 4 - $aanmeldingen->where('id_training', '=', $training->id)->count(); 
                ?>
                @if($beschikbaar && $beschikbaar != 0)
                  <p class="my-5">Er <?php echo ($beschikbaar == 1) ? 'is' : 'zijn' ?> nog {{$beschikbaar}} plek<?php if($beschikbaar != 1){echo 'ken';} ?> beschikbaar</p> 
                  <p class="mb-5">Selecteer een betaaloptie om een plekje te reserveren</p>
                  <a <?php echo 'href="aanmelden/'.$training->id.'"' ?> onclick="event.stopPropagation();"><button class="w-full">Bekijk betaalopties</button></a>
                @else
                  <p class="my-5">Sorry er zijn geen plekken meer beschikbaar voor dit traject</p>
                  <p class="mb-5">U ontvangt een email als er een plek beschikbaar komt</p>
                  <button onclick="event.stopPropagation();" class="w-full uit">Bekijk betaal opties</button>
                @endif
                <a onclick="event.stopPropagation(); showPopUp('{{$training->id}}')"  class="hover:underline underline-offset-2 ml-auto mt-2 w-fit text-sm">Afmelden wachtlijst -></a>
              @endif
            </div>
          @endforeach
        </div>
      @endif

      @if($ceremonies->isNotEmpty())
        <div>
          <h3 id="ceremonie" class="mb-3 mt-20">Ceremonie<?php if($ceremonies->count() > 1){echo 's';}?></h3>
          <div class="ceremonie-container">
            @foreach($ceremonies as $ceremonie)
              <div>
                <?php $datum = new DateTime($ceremonie->datum); ?>
                <div>
                  <img src="{{asset('assets/date.svg')}}" />
                  <p>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}</p>
                </div>
                <div>
                  <img src="{{asset('assets/time.svg')}}" /> 
                  <p>van 11:00 tot je voelt dat je naar huis wilt</p>
                </div>
                <div>
                  <img src="{{asset('assets/location.svg')}}" /> 
                  <p>Schiedam</p>
                </div>
                <a href="https://www.meit.nl/ceremonie"><button>Meer informatie -></button></a>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($intakegesprekken->isNotEmpty())
        <div>
          <h3 id="intakegesprek" class="mb-3 mt-20">Telefonisch intakegesprek</h3>
          <div class="ceremonie-container">
            @foreach($intakegesprekken as $intakegesprek)
              <div>
                <?php 
                  $datum = new DateTime($intakegesprek->datum); 
                  $begin_tijd = new DateTime($intakegesprek->begin_tijd);
                  $eind_tijd = new DateTime($intakegesprek->eind_tijd);
                  $begin_belmoment = new DateTime($intakegesprek->datum . $intakegesprek->begin_tijd);
                  $begin_belmoment->modify('-5 minutes');
                  $eind_belmoment = new DateTime($intakegesprek->datum . $intakegesprek->eind_tijd);
                ?>
                <div>
                  <img src="{{asset('assets/date.svg')}}" />
                  <p>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}</p>
                </div>
                <div>
                  <img src="{{asset('assets/time.svg')}}" /> 
                  <p>{{$begin_tijd->format('H:i')}} - {{$eind_tijd->format('H:i')}}</p>
                </div>
                <?php echo '<script>console.log(`'.json_encode($deelnemer->telefoon_nummer).'`)</script>'; ?>
                @if(isset($admin) && $admin === true)
                  @if($deelnemer->telefoon_nummer)
                    <a href="tel:{{$deelnemer->telefoon_nummer}}"><button class="flex justify-center items-center"><img src="{{asset('assets/telephone.svg')}}"/> Bellen </button></a>
                  @endif
                @else
                  @if($begin_belmoment < new DateTime('00:00:00') && $eind_belmoment > new DateTime('00:00:00'))
                    <a href="tel:06-34733235"><button class="flex justify-center items-center"><img src="{{asset('assets/telephone.svg')}}"/> Bellen </button></a>
                  @else
                    <button class="flex justify-center items-center uit"><img src="{{asset('assets/telephone.svg')}}"/> Bellen </button>
                  @endif
                @endif
              </div>
            @endforeach
          </div>
        </div>

      @endif

      @if($ceremonies->isEmpty() && $intakegesprekken->isEmpty())
        <h3 class="mb-3 mt-20">Ceremonies</h3>
        @if(!isset($admin) || $admin !== true)
          <a href="{{url('ceremonies')}}"><button>Meld je aan voor een telefonisch intakegesprek!</button></a>
        @else 
          <p>Deze deelnemer heeft zich hiervoor nog niet aangemeld</p>
        @endif
      @endif

      <div id="pop-up" onclick="this.classList.add('!hidden')" class="!hidden">
        <div>
          <h4>Weet je zeker dat je je wilt afmelden van de wachtlijst?</h4>
          <p>Je zult geen e-mails ontvangen over dit traject</p>
          <div>
            <a id="afmelden" onclick="event.stopPropagation();"><button>Afmelden</button></a>
            <button class="!cursor-pointer alt-2">Niet afmelden</button>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
<script>
  function showPopUp(id) {
    pop_up = document.getElementById('pop-up');
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('afmelden');

    afmelden.href = "afmelden/" + id;
  }
</script>

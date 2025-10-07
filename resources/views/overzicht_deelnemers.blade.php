<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php 
      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');
      $prijs = Config::get('info.prijs');
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <h2>Hallo {{$deelnemer->voornaam}}</h2>
      @if($trainingen->count() != 0) 
        <h3>Training<?php if($trainingen->count() > 1){echo 'en';}?></h3>
        <div class="trainingen">
          @foreach($trainingen as $training)
            <?php 
              if($betaal_statuses[$training->id] == 2){
                $betaald = true ;
              }else{
                $betaald = false ;
              }
            ?>
            <div onclick="location.href = `{{url('training/'.$training->id)}}`" class="cursor-pointer flex flex-col justify-between gap-2
            <?php if(new DateTime($training->start_moment_4) < new DateTime()){echo 'opacity-75 ';} if($betaald) {echo ' !bg-main-payed';} ?>">
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
                  
                @if($datetime < new DateTime())
                  <h4 class="!text-xl">Sorry de deadline voor het betalen van het tweede termijn is verlopen</h4>
                  <button onclick="event.stopPropagation();" class="w-full uit">Betaal termijn</button>
                @else
                  <a class="mt-3" onclick="event.stopPropagation();" href="#"><button class="w-full">Betaal termijn</button></a>
                @endif
              @endif
            </div>
          @endforeach
        </div>
      @endif
      @if($wachtlijst->isNotEmpty())
        <h5 class="mt-5">Wachtlijst</h5>
        <div class="trainingen">
          @foreach($wachtlijst as $training)
            <div onclick="location.href = `{{url('training/'.$training->id)}}`" class="cursor-pointer flex flex-col justify-between !bg-main-not-payed <?php if(new DateTime($training->start_moment_4) < new DateTime()){echo 'opacity-75';} ?>">
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
              @if($beschikbaar[$training->id] && $beschikbaar[$training->id] != 0)
                <p class="my-5">Er zijn nog {{$beschikbaar[$training->id]}} plek<?php if($beschikbaar[$training->id] != 1){echo 'ken';} ?> beschikbaar</p> 
                <p class="mb-5">Selecteer een betaaloptie om een plekje te reserveren</p>
                <a <?php echo 'href="aanmelden/'.$training->id.'"' ?> onclick="event.stopPropagation();"><button class="w-full">Bekijk betaalopties</button></a>
              @else
                <p class="my-5">Sorry er zijn geen plekken meer beschikbaar voor deze training</p>
                <p class="mb-5">U ontvangt een email als er een plek beschikbaar komt</p>
                <button onclick="event.stopPropagation();" class="w-full uit">Bekijk betaal opties</button>
              @endif
              <a onclick="event.stopPropagation(); showPopUp('{{$training->id}}')"  class="hover:underline underline-offset-2 ml-auto mt-2 w-fit text-sm">Afmelden wachtlijst -></a>
            </div>
          @endforeach
        </div>
      @endif

      @if($ceremonies->isNotEmpty())
        <div>
          <h3 class="mb-3 mt-20">Ceremonie<?php if($ceremonies->count() > 1){echo 's';}?></h3>
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
          <h3 class="mb-3 mt-20">Telefonisch intakegesprek</h3>
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
                @if($begin_belmoment < new DateTime() && $eind_belmoment > new DateTime())
                  <a href="tel:06-34733235"><button class="flex justify-center items-center"><img src="{{asset('assets/telephone.svg')}}"/> Bellen </button></a>
                @else
                  <button class="flex justify-center items-center uit"><img src="{{asset('assets/telephone.svg')}}"/> Bellen </button>
                @endif
              </div>
            @endforeach
          </div>
        </div>

      @endif

      <div id="pop-up" onclick="this.classList.add('!hidden')" class="!hidden">
        <div>
          <h4>Weet je zeker dat je je wilt afmelden van de wachtlijst?</h4>
          <p>Je zult geen e-mails ontvangen over deze training</p>
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

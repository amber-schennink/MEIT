<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('partials.nav')

    <?php

      use Illuminate\Support\Facades\DB;

      $datetime = new DateTime($training->start_moment);

      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');

      $aanmeldingen = DB::table('aanmeldingen')
        ->where('id_training', '=', $training->id)
        ->get();
      $beschikbaar = 4;
      foreach ($aanmeldingen as $key => $val) {
        if($val->betaal_status != 0){
          $beschikbaar--;
        }
      }

      $kop_teksten = [
        'Je energiebron', 
        'Je kernwond (Astrologie)',
        'Je levensmissie (Maya-kalender)',
        'Integratie'
      ];

      $teksten = [
        'Hoe je je energie sterker en stabieler houdt in het dagelijks leven.', 
        'Inzicht in terugkerende patronen en emoties.',
        'Ontdekken welke richting jou vervulling geeft.',
        'Alles samenbrengen en kijken hoe je dit toepast in je leven.'
      ]
    ?>

    <div class="container">
      <h2>Traject</h2>
      <div class="flex flex-col gap-4 my-5">
        @php($i = 1)
        @foreach($training as $key => $moment)
          @if(str_contains($key, 'start_moment'))
            <?php
              $datetime = new DateTime($moment);
              $maand = $datetime->format('m') - 1;
              $time = $datetime->format('H:i');
            ?>
            
            <div class="my-3 border-4 border-main-light py-6 px-8 rounded-xl">
              <h3>Week {{$i}}</h3>
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
              <h6 class="text-xl font-semibold">{{$kop_teksten[$i - 1]}}</h6>
              <p class="text-lg font-semibold">{{$teksten[$i - 1]}}</p>
            </div>
            @php($i++)
            @endif
        @endforeach
      </div>
      <div class="mt-10">
        @if($beschikbaar > 0)
          <p>Er zijn nog {{$beschikbaar}} plaatsen beschikbaar voor dit traject</p>
        @else
          <p>Sorry het is niet meer mogenlijk om je aan te melden voor dit traject.</p> 
          <p>Bekijk mijn <a class="underline underline-offset-2" href="../trainingen">andere trajecten</a> of geef je op voor de wachtlijst (als er een plekje vrij komt neem ik contact met je op)</p>
        @endif

        <div class="w-fit mt-3">
          @include('partials.trainingen_button')
        </div>
      </div>
    </div>
    
    @include('partials.footer')
  </body>
</html>

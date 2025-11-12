<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
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
        'Patronen en Emoties',
        'Richting en vervulling',
        'Integratie'
      ];

      $teksten = [
        'Leer hoe je beter met je eigen energie omgaat en wat je nodig hebt om stabiel en opgeladen te blijven in het dagelijks leven. We werken met bewustwording, ademhaling en praktische oefeningen voor thuis.', 
        'Krijg inzicht in terugkerende emoties of situaties en ontdek waar ze vandaan komen. Je leert begrijpen wat je tegenhoudt en hoe je oude patronen kunt doorbreken.',
        'Ontdek wat jou voldoening geeft, waar jouw natuurlijke energie van gaat stromen en welke richting daarbij past. We kijken samen naar jouw persoonlijke blauwdruk en hoe je die kunt volgen.',
        'We brengen alles samen en vertalen jouw inzichten naar kleine, haalbare stappen voor je dagelijks leven. Je vertrekt met meer rust, richting en vertrouwen in wie je bent.'
      ]
    ?>

    <div class="container">
      <h2>MEIT. traject</h2>
      <p>Het MEIT. Traject is een persoonlijke reis van 4 weken waarin je leert jezelf beter te begrijpen, meer rust te vinden en helderheid te krijgen over <span class="text-second">wie je bent</span> en wat je nodig hebt.</p>
      <p>Elke bijeenkomst duurt 3 uur en vindt plaats in een kleine groep in een gezellige setting bij mijn magische huisje in Schiedam. </p>
      <br>
      <h3>Persoonlijk Profiel</h3>
      <p>Voorafgaand aan het traject ontvang je jouw persoonlijke MEIT. Profiel. In dit profiel vind je informatie over jouw energie, patronen en wat jou echt voldoening geeft. We gebruiken dit profiel als leidraad tijdens alle 4 bijeenkomsten zodat alles wat je leert direct toepasbaar is op jouw eigen leven.</p>
      <br>
      <h3>Wat we per week doen: </h3>
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
              <h4 class="font-semibold">Week {{$i}}: {{$kop_teksten[$i - 1]}}</h4>
              <p class="text-lg mt-2">{{$teksten[$i - 1]}}</p>
              <br>
              <div class="md:flex justify-evenly">
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

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
  <body class="bg-main">
    <?php

      use Illuminate\Support\Facades\Config;
      use Illuminate\Support\Facades\DB;

      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');
      $weekDagen = Config::get('info.weekDagen');

      $file_type = 'ceremonie';
    ?>
    @include('partials.nav')
    @include('partials.flash')

    <div class="container">
      <h2 class="mb-3 wrap-break-word">Ceremonies</h2>
      <p><span class="text-second">Moedig</span> dat je hier bent ♡</p>
      <br>
      <p>Niet iedereen voelt zich geroepen om deze ceremonie te ervaren. Of je nu ervaring hebt met plantmedicijnen of nog niet, je bent meer dan <span class="text-second">welkom</span>. Ik begeleid je van begin tot eind.</p>
      <br>
      <p>Ik beloof je dat het een bijzondere dag wordt. En die ziet er zo uit:</p>
      <div class="ceremonie-list">
        <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/> Je arriveert om 11:00 uur bij mijn magische huisje in Schiedam</p>
        <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/> Rond 12:00 starten we met de ceremonie</p>
        <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/> Rond 13:30 is de ceremonie afgelopen (het effect van het medicijn duurt maximaal 20 minuten)</p>
        <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/> Daarna is er ruimte voor nabespreking</p>
        <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/> Vervolgens verzorg ik voor ons de lunch</p>
        <p><img class="h-5 inline" src="{{asset('assets/point_right.svg')}}"/> Rond 16:00 uur ronden we de dag meestal af</p>
      </div>
      <br>
      <p>Het effect van het medicijn is kort, maar de ervaring is <span class="text-second">intens</span>. Daarom is het belangrijk dat je de ruimte neemt om te verwerken wat je hebt ervaren. Er wordt veel in beweging gezet tijdens en na de ceremonie.</p>
      <br>
      <p>Om die reden raad ik je aan om de dag zelf en de dag erna volledig vrij te houden van afspraken of andere verplichtingen. Zie het als een <span class="text-second">mini-vakantie</span> voor jezelf. 😉</p>
      <br>
      <p>De kosten voor de gehele dag bedragen €444,-.</p>
      <br>
      <h3>Belangrijk</h3>
      <p>Als je 72 uur voor dat de  ceremonie plaatsvindt antidepressiva of andere zware medicatie slikt, dan kan ik de ceremonie niet doen.</p>
      <br>
      <p>Tepezcohuite werkt geestverruimend, terwijl antidepressiva of andere zware medicatie geestvernauwend werken.</p>
      <br>
      <br>
      <h5>Kies hieronder jouw datum.</h5>
      <div class="trainingen mt-6">
        @php
          $ceremoniesIsEmpty = true;

          $deadline = New DateTime();
          $deadline->modify('+5 days');
        @endphp
        @foreach($ceremonies as $key => $ceremonie)
          @if(new DateTime($ceremonie->datum . '11:00:00') < $deadline)
            @continue
          @endif
          @php 
            if($ceremoniesIsEmpty){
              $ceremoniesIsEmpty = false;
            }
            $datetime = new DateTime($ceremonie->datum);
            $maand = $datetime->format('m') - 1;
          @endphp
          @if($ceremonie->id_deelnemer)
          <div class="flex flex-col justify-between h-fit bg-main-light/70!">
          @else
          <div class="flex flex-col justify-between h-fit">
          @endif
            
            <div class="bg-main rounded px-2 py-6">
              <p class="mx-auto w-fit">{{$weekDagen[$datetime->format('w')]}}</p>
              <h4 class="mx-auto w-fit">{{$datetime->format('j')}} {{$maanden[$maand]}} {{$datetime->format('Y')}}</h4>
              <p class="mx-auto w-fit">11:00 - 16:00</p>
            </div>
            @if($ceremonie->id_deelnemer)
            <div class="h-[48px] mt-4 flex"><p class="m-auto text-center">Deze datum is niet meer beschikbaar</p></div>
            @else
            <a href="/ceremonie/{{$ceremonie->id}}"><button class="mt-4 w-full">Aanmelden</button></a>
            @endif
          </div>
        @endforeach
      </div>
      @if($ceremonies->isEmpty() || $ceremoniesIsEmpty)
        <p>Er zijn momenteel geen ceremonies beschikbaar.</p>
        <p>Hou de socials van MEIT. in de gaten voor de laatste updates en nieuwe data ✨</p>
        <p>Al ingeschreven voor een ceremonie? <a class="hover:underline underline-offset-2 text-second" href="{{url('login')}}">Log in</a> om de ceremonies te bekijken</p>
      @endif
      <br>
      <p>Komt geen van de beschikbare data voor jou goed uit, maar heb je wel een specifieke dag in gedachten? Stuur me dan even een <a href="mailto:welkom@meit.nl">mail</a>, dan kijken we samen naar de mogelijkheden.</p>
    </div>

    @include('partials.footer')
  </body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php

      use Illuminate\Support\Facades\DB;

      $datetime = new DateTime($training->start_moment);

      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');

      $aanmeldingen = DB::table('aanmeldingen')
        ->where('id_training', '=', $training->id)
        ->select('id_deelnemer', 'betaal_status')->get();
      $beschikbaar = 4;
      foreach ($aanmeldingen as $key => $val) {
        if($val->betaal_status != 0){
          $beschikbaar--;
        }
      }

      $teksten = [
        'Je energiebron - hoe je je energie sterker en stabieler houdt in het dagelijks leven.', 
        'Je kernwond (Astrologie) - inzicht in terugkerende patronen en emoties.',
        'Je levensmissie (Maya-kalender) - ontdekken welke richting jou vervulling geeft.',
        'Integratie - alles samenbrengen en kijken hoe je dit toepast in je leven.'
      ]
    ?>

    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <h2>Training</h2>
      <div class="flex flex-col gap-4 my-5">
        @php($i = 1)
        @foreach($training as $key => $moment)
          @if(str_contains($key, 'start_moment'))
            <?php
              $datetime = new DateTime($moment);
              $maand = $datetime->format('m') - 1;
              $time = $datetime->format('H:i');
            ?>
            
            <div>
              <h3>Week {{$i}}</h3>
              <p>{{$datetime->format('j')}} {{$maanden[$maand]}}</p>
              <p>{{$time}} - {{date('H:i', strtotime($time) + 60*60*3)}}</p>
              <p>{{$teksten[$i - 1]}}</p>
            </div>
            @php($i++)
            @endif
        @endforeach
      </div>
      <div class="mt-10">
          
        @if($beschikbaar > 0)
          <p>Er zijn nog {{$beschikbaar}} plaatsen beschikbaar voor deze training</p>
        @else
          <p>Sorry het is niet meer mogenlijk om je aan te melden voor deze training.</p> 
          <p>Bekijk mijn <a class="underline underline-offset-2" href="../trainingen">andere trainingen</a> of geef je op voor de wachtlijst (als er een plekje vrij komt neem ik contact met je op)</p>
        @endif
        @if(session('login') && session('id') && session('admin') == false && $aanmeldingen->contains('id_deelnemer', session('id')))
          <?php $betaal_status = $aanmeldingen->where('id_deelnemer', '=',  session('id'))->first()->betaal_status; ?>
          @if($betaal_status == 0)
            @if($beschikbaar > 0)
              <p class="text-xs">(Rond betaling af om je plek te garanderen)</p>
              <a <?php echo 'href="../aanmelden/'.$training->id.'"' ?>><button class="alt">Op wachtlijst</button></a>
            @else
              <button class="alt-3">Op wachtlijst</button>
            @endif
          @elseif($betaal_status != 0)
            <button class="alt-2">Aangemeld</button>
          @endif
        @else
          @if($beschikbaar > 0)
            <a <?php echo 'href="../aanmelden/'.$training->id.'"' ?>><button class="mt-3">Aanmelden</button></a>
          @else
            <button <?php echo 'href="../aanmelden/'.$training->id.'"' ?> class="mt-3 alt">Opgeven wachtlijst</button>
          @endif
        @endif
      </div>
    </div>
  </body>
</html>

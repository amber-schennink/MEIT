<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
  <body class="bg-main">
    @include('partials.nav')

    <?php
      use Illuminate\Support\Facades\DB;

      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');
      
      $deadline = new DateTime('00:00:00');
      $deadline->modify('+7 days');
    ?>
    <div class="container">
      <h2 class="mb-3 wrap-break-word">MEIT. Traject</h2>
      <p class="mb-6">Kies hieronder de startdatum van jouw Traject. Elke reeks bestaat uit 4 bijeenkomsten van 3 uur, op vaste dagen en tijden. Bekijk goed of je op alle data aanwezig kunt zijn voordat je je plek reserveert.</p>
      <div class="trainingen">
        @foreach($trainingen as $key => $training)
          @if(new DateTime($training->start_moment_4) < new DateTime('00:00:00'))
            @continue
          @endif
          <?php
            $aanmeldingen = DB::table('aanmeldingen')
              ->where('id_training', '=', $training->id)->get();
            $beschikbaar = 4;
            foreach ($aanmeldingen as $key => $val) {
              if($val->betaal_status != 0){
                $beschikbaar--;
              }
            }
          ?>
          @if(new DateTime($training->start_moment) < $deadline)
          <div class="flex flex-col justify-between opacity-70 order-1">
          @else
          <div class="flex flex-col justify-between">
          @endif
            <div class="datums">
              @foreach($training as $key => $val)
                @if(str_contains($key, 'start_moment'))
                  @php
                    $datetime = new DateTime($val);
                    $maand = $datetime->format('m') - 1;
                  @endphp
                  <div>
                    <p>{{$datetime->format('j')}}</p>
                    <p>{{substr($maanden[$maand], 0, 3)}}</p>
                  </div>
                @endif
              @endforeach
            </div>
            @php
              $beginTijd = new DateTime($training->start_moment);
              $eindTijd = new DateTime($training->start_moment);
              $eindTijd->modify('+3 hours');
            @endphp
            <p>{{$beginTijd->format('H:i')}} - {{$eindTijd->format('H:i')}}</p>
            @if($beschikbaar > 0)
              <p class="my-5">Er <?php echo ($beschikbaar == 1) ? 'is' : 'zijn' ?> nog {{$beschikbaar}} plek<?php if($beschikbaar != 1){echo 'ken';} ?> beschikbaar</p>
            @else
              <p class="my-5">Sorry er zijn geen plekken meer beschikbaar voor dit traject</p>
            @endif
            <div>
              <p class="mb-2"><a class="hover:underline underline-offset-2" <?php echo 'href="training/'.$training->id.'"' ?> >meer info -></a></p>

              @include('partials.trainingen_button')
            </div>
          </div>
        @endforeach
      </div>
      @if($trainingen->isEmpty())
        <p>Er zijn momenteel geen trajecten beschikbaar.</p>
        <p>Hou de socials van MEIT. in de gaten voor de laatste updates en nieuwe data ✨</p>
        <p>Al ingeschreven voor een traject? <a class="hover:underline underline-offset-2 text-second" href="{{url('login')}}">Log in</a> om de trajecten te bekijken</p>
      @endif
    </div>

    @include('partials.footer')
  </body>
</html>
<style>
  .trainingen button {
    width: 100%;
  }
</style>
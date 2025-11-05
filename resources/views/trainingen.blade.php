<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
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
      <h2 class="mb-3 wrap-break-word">Traject MEIT. Transformatieproces</h2>
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
          <div class="flex flex-col justify-between opacity-70">
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
    </div>

    @include('partials.footer')
  </body>
</html>
<style>
  .trainingen button {
    width: 100%;
  }
</style>
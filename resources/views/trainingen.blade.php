<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php
      use Illuminate\Support\Facades\DB;

      $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
    ?>
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <h2>Trainingen</h2>
      <div class="trainingen">
        @foreach($trainingen as $key => $training)
          @if(new DateTime($training->start_moment_4) < new DateTime())
            @continue
          @endif
          <?php
            $aanmeldingen = DB::table('aanmeldingen')
              ->where('id_training', '=', $training->id)
              ->select('id_deelnemer', 'betaal_status')->get();
            $beschikbaar = 4;
            foreach ($aanmeldingen as $key => $val) {
              if($val->betaal_status != 0){
                $beschikbaar--;
              }
            }
          ?>
          <div class="flex flex-col justify-between">
            <div class="datums">
              @foreach($training as $key => $val)
                @if(str_contains($key, 'start_moment'))
                  @php
                    $datetime = new DateTime($val);
                    $maand = $datetime->format('m') - 1;
                  @endphp
                  <div>
                    <p>{{ltrim($datetime->format('d'), '0')}}</p>
                    <p>{{substr($maanden[$maand], 0, 3)}}</p>
                  </div>
                @endif
              @endforeach
            </div>
            @if($beschikbaar > 0)
              <p class="my-5">Er zijn nog {{$beschikbaar}} plek<?php if($beschikbaar != 1){echo 'ken';} ?> beschikbaar</p>
            @else
              <p class="my-5">Sorry er zijn geen plekken meer beschikbaar voor deze training</p>
            @endif
            <div>
              <p class="mb-2"><a class="hover:underline underline-offset-2" <?php echo 'href="training/'.$training->id.'"' ?> >meer info -></a></p>

              @if(session('login') && session('id') && session('admin') == false && $aanmeldingen->contains('id_deelnemer', session('id')))
                <?php $betaal_status = $aanmeldingen->where('id_deelnemer', '=',  session('id'))->first()->betaal_status; ?>
                @if($betaal_status == 0)
                  @if($beschikbaar > 0)
                    <p class="text-xs m-auto w-fit">(Rond betaling af om je plek te garanderen)</p>
                    <a <?php echo 'href="../aanmelden/'.$training->id.'"' ?>><button class="alt w-full">Op wachtlijst</button></a>
                  @else
                    <button class="alt-3 w-full">Op wachtlijst</button>
                  @endif
                @elseif($betaal_status != 0)
                  <button class="alt-2 w-full">Aangemeld</button>
                @endif
              @else
                @if($beschikbaar > 0)
                  <a <?php echo 'href="../aanmelden/'.$training->id.'"' ?>><button class="w-full">Aanmelden</button></a>
                @else
                  <a <?php echo 'href="../aanmelden/'.$training->id.'"' ?>><button class="alt w-full">Opgeven wachtlijst</button></a>
                @endif
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>

  </body>
</html>

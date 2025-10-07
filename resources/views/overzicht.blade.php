<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php

      use Illuminate\Support\Facades\DB;

      use Illuminate\Support\Facades\Config;

use function PHPUnit\Framework\isEmpty;

      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');

      $datum = new DateTime();
      $datum->modify('-7 days');
      $aanmeldingen_afgelopen_week = $aanmeldingen->where('created_at', '>=', $datum->format('Y-m-d'));
    
      $aanmeldingen_gesorteerd = [];
      foreach($aanmeldingen_afgelopen_week as $aanmelding){
        $aanmeldingen_gesorteerd[$aanmelding->id_training][] = $aanmelding;
      }
      $intakegesprekken_afgelopen_week = $intakegesprekken->where('created_at', '>=', $datum->format('Y-m-d'));
      $intakegesprekken_gesorteerd = [];
      foreach($intakegesprekken_afgelopen_week as $intakegesprek){
        $intakegesprekken_gesorteerd[$intakegesprek->datum][] = $intakegesprek;
      }
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div id="trainingen">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2>Overzicht</h2>
        </div>
        <div class="my-5">
          <a class="w-fit block" href="{{url('trainingen')}}"><h3 class="w-fit">Trainingen</h3></a>
          @if($aanmeldingen_gesorteerd)
            <h5 class="my-2 !text-white">Er zijn {{$aanmeldingen_afgelopen_week->count()}} nieuwe aanmeldingen sinds vorige week</h5>
            <div class="trainingen">
              @foreach($aanmeldingen_gesorteerd as $key => $aanmeldingen)
                <?php 
                  $training = $trainingen->where('id', '=', $key)->first();
                ?>
                <div class="cursor-pointer !justify-start" onclick="location.href=`{{url('trainingen#' . $key)}}`">

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
                  @foreach($aanmeldingen as $aanmelding)
                    <?php 
                      $created = new DateTime($aanmelding->created_at);
                      $deelnemer = $deelnemers->where('id', '=', $aanmelding->id_deelnemer)->first();
                    ?>
                    <p class="mx-auto text-main font-semibold mt-6">{{$created->format('j-m-Y')}}</p>
                    <div class="blokken items-center">
                      <div>
                        <img src="{{asset('assets/user.svg')}}" /> 
                        <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
                      </div>
                      @if($aanmelding->betaal_status == 2)
                        <p class="text-green-400 betaal-status">Betaald</p>
                      @elseif($aanmelding->betaal_status == 1)
                        <?php 
                          $deadline = new DateTime($training->start_moment);
                          $deadline->modify('-7 day');
                        ?>
                        <p class="text-orange-400 betaal-status">In termijnen <br> (deadline {{$deadline->format('j')}} {{$maanden[$deadline->format('m') - 1]}})</p>
                        @if($deadline < new DateTime())
                          <p class="text-red-400 betaal-status">Deadline is verstreken!</p>
                        @endif
                      @else
                        <p class="text-red-400  betaal-status">Op wachtlijst</p>
                      @endif
                    </div>
                  @endforeach
                </div>
              @endforeach
            </div>
          @else
            <h5 class="my-2 !text-white">Er zijn nog geen nieuwe aanmeldingen sinds vorige week</h5>
          @endif
        </div>
        <div class="my-5">
          <a class="w-fit block" href="{{url('ceremonies')}}"><h3 class="w-fit">Ceremonies</h3></a>
            @if($intakegesprekken_gesorteerd)
              <h5 class="my-2 !text-white">Er zijn {{$intakegesprekken_afgelopen_week->count()}} nieuwe intake gesprekken ingepland sinds vorige week</h5>
              <div class="trainingen">
                @foreach($intakegesprekken_gesorteerd as $key => $gesprekken)
                  <div class="!justify-start">
                    <?php $datum = new Datetime($key) ?>
                    <p class="mx-auto text-main font-semibold">{{$datum->format('j-m-Y')}}</p>
                    @foreach($gesprekken as $gesprek)
                      <?php
                       $begin_tijd = new DateTime($gesprek->begin_tijd);
                       $eind_tijd = new DateTime($gesprek->eind_tijd);
                      ?>
                      <div class="blokken">
                        <p class="text-center">{{$begin_tijd->format('H:i')}} - {{$eind_tijd->format('H:i')}}</p>
                      </div>
                    @endforeach
                  </div>
                @endforeach
              </div>
            @else
              <h5 class="my-2 !text-white">Er zijn nog geen nieuwe intake gesprekken ingepland sinds vorige week</h5>
            @endif
        </div>
      </div>
      <div>
        <div class="schema">
          <img id="schema-knop-l" onclick="scrollSchema('l')" class="-left-[5%] uit" src="{{asset('assets/arrow_left.svg')}}" />
          <div class="tijden pointer-events-none">
            @for($i = 0; $i <= $schema_eindig->format('H')-$schema_start->format('H'); $i++)
              <div class="flex items-center">
                <p class="w-12 min-w-12">{{$i + $schema_start->format('H')}}:{{$schema_start->format('i')}}</p>
                <div class="bg-main-light h-0.5 w-full"></div>
              </div>
            @endfor
          </div>
          <div id="scroll-container">
            @for($i = 0; $i < 10; $i++)
              <div class="schema-block" style="grid-template-rows: 24px  <?php echo ($schema_eindig->format('H') - $schema_start->format('H')) * 50 . 'px;'; ?>">
                <?php 
                  $datum = new DateTime(); 
                  $datum->modify('last sunday +1 day');
                  $datum->modify('+'. $i . 'weeks')
                ?>
                @for($j = 1; $j <= 7; $j++)
                  <?php 
                    $data = [];
                    $data['ceremonies'] = $ceremonies->where('datum', '=', $datum->format('Y-m-d')); 
                    $data['intakegesprekken'] = $intakegesprekken->where('datum', '=', $datum->format('Y-m-d')); 
                    $data['mogenlijkheden'] = $intake_mogenlijkheden->where('datum', '=', $datum->format('Y-m-d')); 
                    $next_datum = new DateTime($datum->format('Y-m-d'));
                    $next_datum->modify('+1 day');

                    $data['trainingen'] = DB::table('trainingen')->where(
                      [['start_moment', '>=', $datum->format('Y-m-d H:i:s')],['start_moment', '<=', $next_datum->format('Y-m-d H:i:s')]]
                    )->orWhere(function ($query) use ($datum, $next_datum){
                      $query->where('start_moment_2', '>=', $datum->format('Y-m-d H:i:s'))->where('start_moment_2', '<=', $next_datum->format('Y-m-d H:i:s'));
                    })->orWhere(function ($query) use ($datum, $next_datum){
                      $query->where('start_moment_3', '>=', $datum->format('Y-m-d H:i:s'))->where('start_moment_3', '<=', $next_datum->format('Y-m-d H:i:s'));
                    })->orWhere(function ($query) use ($datum, $next_datum){
                      $query->where('start_moment_4', '>=', $datum->format('Y-m-d H:i:s'))->where('start_moment_4', '<=', $next_datum->format('Y-m-d H:i:s'));
                    })->get();
                  ?>
                  <h6>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}</h6>
                  <div id="{{$datum->format('Y-m-d')}}">
                    @foreach($data as $key => $col)
                      @foreach($col as $val)
                        <?php echo setSchemaData($key, $val, $datum, 'overzicht') ?>
                      @endforeach
                    @endforeach

                    <div class="ghost-block hidden">
                      <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
                    </div>
                  </div>
                  <?php $datum->modify('+1 day') ?>
                @endfor
              </div>
            @endfor
          </div>
          <img id="schema-knop-r" onclick="scrollSchema('r')" class="-right-[5%]" src="{{asset('assets/arrow_right.svg')}}" />
        </div>
        <div class="mt-4 ml-[10%]">
          <p class="before:content-[''] before:bg-intakegesprekken before:h-5 before:w-5 before:block flex gap-2">Intakegesprekken</p>
          <p class="before:content-[''] before:bg-mogenlijkheden before:h-5 before:w-5 before:block flex gap-2">Intakegesprek mogenlijkheden</p>
          <p class="before:content-[''] before:bg-ceremonies before:h-5 before:w-5 before:block flex gap-2">Ceremonies</p>
          <p class="before:content-[''] before:bg-trainingen before:h-5 before:w-5 before:block flex gap-2">Trainingen</p>
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

    afmelden.href = "training_verwijderen/" + id;
  }
  function scrollSchema(side) {
    var container = document.getElementById('scroll-container')
    var knopL = document.getElementById('schema-knop-l')
    var knopR= document.getElementById('schema-knop-r')
    if(side == 'l'){
      w = container.getBoundingClientRect().width;
      container.scrollBy(-w, 0)
      if(container.scrollLeft <= Math.ceil(w)){
        knopL.classList.add('uit');
      }
      knopR.classList.remove('uit');
    }else{
      container.scrollBy(container.getBoundingClientRect().width, 0)
      knopL.classList.remove('uit');
      w = container.scrollWidth - (Math.ceil(container.getBoundingClientRect().width) * 2)
      if(container.scrollLeft >= w){
        knopR.classList.add('uit');
      }
    }
  }
</script>
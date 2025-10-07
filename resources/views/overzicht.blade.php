<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php

      use Illuminate\Support\Facades\DB;

      use Illuminate\Support\Facades\Config;

      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');

      $datum = new DateTime();
      $datum->modify('-7 days');
      $aanmeldingen_afgelopen_week = $aanmeldingen->where('created_at', '>=', $datum->format('Y-m-d'))->count();
      $ceremonies_afgelopen_week = $ceremonies->where('created_at', '>=', $datum->format('Y-m-d'))->count();
      $intakegesprekken_afgelopen_week = $intakegesprekken->where('created_at', '>=', $datum->format('Y-m-d'))->count();
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div>
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2>Overzicht</h2>
        </div>
        <div class="mt-5 mb-7 trainingen">
          <div onclick="location.href = `{{url('trainingen')}}`" class="text-center !bg-trainingen cursor-pointer">
            <p class="text-3xl">{{$aanmeldingen_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe aanmelding<?php if($aanmeldingen_afgelopen_week != 1) echo 'en'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
          <div onclick="location.href = `{{url('ceremonies')}}`" class="text-center !bg-ceremonies cursor-pointer">
            <p class="text-3xl">{{$ceremonies_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe ceremonie<?php if($ceremonies_afgelopen_week != 1) echo 's'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
          <div onclick="location.href = `{{url('ceremonies#intakegesprekken')}}`" class="text-center !bg-intakegesprekken cursor-pointer">
            <p class="text-3xl">{{$intakegesprekken_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe intakegesprek<?php if($intakegesprekken_afgelopen_week != 1) echo 'ken'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
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
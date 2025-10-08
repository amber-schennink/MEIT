<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php 
      use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div id="ceremonies">
        <h2 class="mb-3">Ceremonies</h2>
        <div class="ceremonie-container">
          @foreach($ceremonies as $ceremonie)
            <?php 
              $datum = new DateTime($ceremonie->datum);
              $deelnemer = $deelnemers->where('id', '=', $ceremonie->id_deelnemer)->first();
            ?>
            <div>
              <div>
                <img src="{{asset('assets/date.svg')}}" /> 
                <p>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}</p>
              </div>
              <div>
                <img src="{{asset('assets/time.svg')}}" /> 
                <p>11:00</p>
              </div>
              <div class="flex items-center">
                <img src="{{asset('assets/user.svg')}}" /> 
                <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
              </div>
              <div>
                <img src="{{asset('assets/email.svg')}}" /> 
                <a class="hover:underline underline-offset-2" href="mailto: {{$deelnemer->email}}"><p>{{$deelnemer->email}}</p></a>
              </div>
              <div>
                @if(isset($deelnemer->telefoon_nummer))
                  <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                    <img src="{{asset('assets/telephone.svg')}}" /> 
                    <p>{{$deelnemer->telefoon_nummer}}</p>
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>
        <div id="intakegesprekken">
          <h3 class="mb-3 mt-6">Intakegesprekken</h3>
          <div class="ceremonie-container intake">
            @foreach($intakegesprekken as $intakegesprek)
              <?php 
                $datum = new DateTime($intakegesprek->datum);
                $begin_tijd = new DateTime($intakegesprek->begin_tijd);
                $eind_tijd = new DateTime($intakegesprek->eind_tijd);
                
                if($intakegesprek->id_deelnemer != null){
                  $deelnemer = $deelnemers->where('id', '=', $intakegesprek->id_deelnemer)->first();
                }else{
                  $deelnemer = null;
                }
              ?>
              <div>
                <div>
                  <img src="{{asset('assets/user.svg')}}" /> 
                  <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
                </div>
                <div>
                  <a class="hover:underline underline-offset-2 flex items-center" href="mailto:{{$deelnemer->email}}">
                    <img src="{{asset('assets/email.svg')}}" /> 
                    <p>{{$deelnemer->email}}</p>
                  </a>
                </div>
                <div>
                  @if(isset($deelnemer->telefoon_nummer))
                    <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                      <img src="{{asset('assets/telephone.svg')}}" /> 
                      <p>{{$deelnemer->telefoon_nummer}}</p>
                    </a>
                  @endif
                </div>
                <div>
                  <img src="{{asset('assets/date.svg')}}" /> 
                  <p>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}</p>
                </div>
                <div>
                  <img src="{{asset('assets/time.svg')}}" /> 
                  <p>{{$begin_tijd->format('H:i')}} - {{$eind_tijd->format('H:i')}}</p>
                </div>
                <a href="{{url('ceremonies/'.$intakegesprek->id)}}"><button>Plan ceremonie</button></a>
              </div>
            @endforeach
          </div>
        </div>
        <div>
          <div class="flex items-center">
            <h4 class="text-second mb-3 mt-6">Intake mogelijkheden</h4>
          </div>
          <div class="schema">
            <img id="schema-knop-l" onclick="scrollSchema('l')" class="-left-[5%] uit" src="{{asset('assets/arrow_left.svg')}}" />
            <div class="tijden pointer-events-none">
              @for($i = 0; $i <= $schema_eindig->format('H') - $schema_start->format('H'); $i++)
                <div class="flex items-center">
                  <p class="w-12 min-w-12">{{$i + $schema_start->format('H')}}:{{$schema_start->format('i')}}</p>
                  <div class="bg-main-light h-0.5 w-full"></div>
                </div>
              @endfor
            </div>
            <div id="scroll-container">
              @for($i = 0; $i < 5; $i++)
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
                      $data['mogelijkheden'] = $intake_mogelijkheden->where('datum', '=', $datum->format('Y-m-d')); 
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
                    

                    @if($data['mogelijkheden']->isEmpty() && $data['intakegesprekken']->isEmpty())
                      <div id="{{$datum->format('Y-m-d')}}" class="cursor-pointer" <?php echo 'onclick="setDatum(`'. $datum->format('Y-m-d') .'`)"';?>>
                    @else
                      <div id="{{$datum->format('Y-m-d')}}">
                    @endif
                      @foreach($data as $key => $col)
                        @foreach($col as $val)
                          <?php echo setSchemaData($key, $val, $datum, 'overzicht_ceremonies') ?>
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
            <p class="before:content-[''] before:bg-mogelijkheden before:h-5 before:w-5 before:block flex gap-2">Intakegesprek mogelijkheden</p>
            <p class="before:content-[''] before:bg-ceremonies before:h-5 before:w-5 before:block flex gap-2">Ceremonies</p>
            <p class="before:content-[''] before:bg-trainingen before:h-5 before:w-5 before:block flex gap-2">Trainingen</p>
          </div>
          <form action="{{url('gesprek_mogelijkheden')}}" method="POST" class="m-auto w-fit">
            @csrf
            <input onchange="setDatum(this.value)" id="mogenlijkheid-form-datum" name="datum" type="date" required/>
            <input class="ml-4 mr-2" onchange="setTijden(this.value, document.getElementById('mogenlijkheid-form-eind-tijd').value)" id="mogenlijkheid-form-begin-tijd" name="begin_tijd" type="time" required />
            <input class="mr-4 ml-2" onchange="setTijden(document.getElementById('mogenlijkheid-form-begin-tijd').value, this.value)" id="mogenlijkheid-form-eind-tijd" name="eind_tijd" type="time" required />
            <button id="mogenlijkheid-form-button" class="mb-3 mt-6 ml-auto" type="submit">Voeg intake mogenlijkheid toe</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
<style>
  .\!bg-intakegesprekken, .before\:bg-intakegesprekken::before , 
  .\!bg-ceremonies, .before\:bg-ceremonies::before, 
  .\!bg-trainingen, .before\:bg-trainingen::before{
    opacity: 70%;
  }
</style>
@if($errors->any())
  <script>
    <?php echo 'alert("'.$errors->first().'");'; ?>
    document.getElementById('mogenlijkheid-form-datum').value ='';
    document.getElementById('mogenlijkheid-form-begin-tijd').value = '';
    document.getElementById('mogenlijkheid-form-eind-tijd').value = '';
  </script>
@endif

<script>
  var schema_start = '<?php echo str_pad($schema_start->format('H:i'), 5, '0', STR_PAD_LEFT); ?>';
    var [uur_start, min_start] = schema_start.split(':');
  var schema_eindig = '<?php echo str_pad($schema_eindig->format('H:i'), 5, '0', STR_PAD_LEFT); ?>'; 
  var input_begin = document.getElementById('mogenlijkheid-form-begin-tijd')
  var input_eind = document.getElementById('mogenlijkheid-form-eind-tijd')
  var button = document.getElementById('mogenlijkheid-form-button')

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
  function setDatum(datum){
    block = document.getElementById(datum)
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden)')
    ghosts.forEach(ghost => {
      ghost.classList.add('hidden')
    });
    if(block){
      ghost_block = block.getElementsByClassName('ghost-block')[0]
      ghost_block.classList.remove('hidden')
    }
    document.getElementById('mogenlijkheid-form-datum').value = datum
  }
  function setTijden(begintijd, eindtijd){
    ghosts = document.querySelectorAll('.ghost-block')
    input_begin.style.border = "none"
    input_eind.style.border = "none"
    var [uur_begin, min_begin] = begintijd.split(':');
    var [uur_eind, min_eind] = eindtijd.split(':');
    button.classList.remove('uit')

    if(begintijd){
      if(begintijd < schema_start || begintijd > schema_eindig){
        input_begin.style.border = "red solid 2px"
        button.classList.add('uit')
        if(ghosts[0].getElementsByClassName('ghost-begin-tijd')[0].innerHTML != "00:00"){
          ghosts.forEach(ghost => {
            ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = "00:00";
            ghost.style.marginTop = "0px";
          })
          var [uur_begin, min_begin] = schema_start.split(':');
        }
      }else{
        m_top = (uur_begin - uur_start) * 50 + ((min_begin - min_start) / 60) * 50;

        ghosts.forEach(ghost => {
          ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = begintijd;
          ghost.style.marginTop = m_top + "px";
        });
      }
    }else{
      var [uur_begin, min_begin] = schema_start.split(':');
    }
    if(eindtijd){
      if(eindtijd < schema_start || eindtijd > schema_eindig){
        input_eind.style.border = "red solid 2px"
        if(ghosts[0].getElementsByClassName('ghost-eind-tijd')[0].innerHTML != "00:00"){
          ghosts.forEach(ghost => {
            ghost.getElementsByClassName('ghost-eind-tijd')[0].innerHTML = "00:00";
            ghost.style.height = "50px";
          })
        }
        button.classList.add('uit')
      }else{
        duur_uur = uur_eind - uur_begin
        duur_min = min_eind - min_begin
        if(duur_min < 0){
          duur_min = 60 + duur_min
          duur_uur--
        }
        height = duur_uur * 50 + (duur_min / 60) * 50 
        
        ghosts.forEach(ghost => {
          ghost.getElementsByClassName('ghost-eind-tijd')[0].innerHTML = eindtijd;
          ghost.style.height = height + "px";
        });
      }
      
    }
    if(begintijd && eindtijd && begintijd > eindtijd){
      input_begin.style.border = "red solid 2px"
      input_eind.style.border = "red solid 2px"
      button.classList.add('uit')
    }

  }
</script>
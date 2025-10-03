<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php 
      $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
      $schema_start = 8;
      $schema_eindig = 20;
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
                <p>{{ltrim($datum->format('d'), '0')}} {{$maanden[$datum->format('m') - 1]}}</p>
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
        <div>
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
                  <p>{{ltrim($datum->format('d'), '0')}} {{$maanden[$datum->format('m') - 1]}}</p>
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
            <h4 class="text-second mb-3 mt-6">Intake mogenlijkheden</h4>
          </div>
          <div class="schema">
            <img id="schema-knop-l" onclick="scrollSchema('l')" class="-left-[5%] uit" src="{{asset('assets/arrow_left.svg')}}" />
            <div class="tijden pointer-events-none">
              @for($i = 0; $i <= $schema_eindig-$schema_start; $i++)
                <div class="flex items-center">
                  <p class="w-12 min-w-12">{{$i + $schema_start}}:00</p>
                  <div class="bg-main-light h-0.5 w-full"></div>
                </div>
              @endfor
            </div>
            <div id="scroll-container">
              @for($i = 0; $i < 5; $i++)
                <div class="schema-block">
                  <?php 
                    $datum = new DateTime(); 
                    $datum->modify('last sunday +1 day');
                    $datum->modify('+'. $i . 'weeks')
                  ?>
                  @for($j = 1; $j <= 7; $j++)
                    <?php $mogenlijkheden = $intake_mogenlijkheden->where('datum', '=', $datum->format('Y-m-d')); ?>
                    <h6>{{$datum->format('d')}} {{$maanden[$datum->format('m') - 1]}}</h6>
                    <div id="{{$datum->format('Y-m-d')}}" class="cursor-pointer" <?php echo 'onclick="setDatum(`'. $datum->format('Y-m-d') .'`)"';?>>
                      @foreach($mogenlijkheden as $mogenlijkheid)
                        <?php 
                          $begin_tijd = new DateTime($mogenlijkheid->begin_tijd);
                          $eind_tijd = new DateTime($mogenlijkheid->eind_tijd);
                          $duur = $begin_tijd->diff($eind_tijd);
                          $top = ($begin_tijd->format('H') - $schema_start) * 50 + ($begin_tijd->format('i') / 60) * 50;
                          $height = $duur->h * 50 + ($duur->i / 60) * 50 
                        ?>
                        <div <?php echo 'style="top: '. $top .'px;height: '. $height .'px; "'?>>
                          <p>{{$begin_tijd->format('H:i')}} - {{$eind_tijd->format('H:i')}}</p>
                        </div>
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
          <form action="{{url('gesprek_mogenlijkheden')}}" method="POST" class="m-auto w-fit">
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
@if($errors->any())
  <script>
    <?php echo 'alert("'.$errors->first().'");'; ?>
    document.getElementById('mogenlijkheid-form-datum').value ='';
    document.getElementById('mogenlijkheid-form-begin-tijd').value = '';
    document.getElementById('mogenlijkheid-form-eind-tijd').value = '';
  </script>
@endif

<script>
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
    input_begin = document.getElementById('mogenlijkheid-form-begin-tijd')
    input_eind = document.getElementById('mogenlijkheid-form-eind-tijd')
    input_begin.style.border = "none"
    input_eind.style.border = "none"
    ghosts = document.querySelectorAll('.ghost-block')
    button = document.getElementById('mogenlijkheid-form-button')
    var [uur_begin, min_begin] = begintijd.split(':');
    var [uur_eind, min_eind] = eindtijd.split(':');
    button.classList.remove('uit')

    schema_start = '<?php if(strlen($schema_start) <2){echo '0';} echo $schema_start ?>';
    schema_eindig = '<?php if(strlen($schema_eindig) <2){echo '0';} echo $schema_eindig ?>';

    if(begintijd){
      if(begintijd < schema_start+':00' || begintijd > schema_eindig+':00'){
        input_begin.style.border = "red solid 2px"
        button.classList.add('uit')
        if(ghosts[0].getElementsByClassName('ghost-begin-tijd')[0].innerHTML != "00:00"){
          ghosts.forEach(ghost => {
            ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = "00:00";
            ghost.style.marginTop = "0px";
          })
          uur_begin = schema_start
          min_begin = "00"
        }
      }else{
        m_top = (uur_begin - schema_start) * 50 + (min_begin / 60) * 50;

        ghosts.forEach(ghost => {
          ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = begintijd;
          ghost.style.marginTop = m_top + "px";
        });
      }
    }else{
      uur_begin = schema_start
      min_begin = '00'
    }
    if(eindtijd){
      if(eindtijd < schema_start+':00' || eindtijd > schema_eindig+':00'){
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
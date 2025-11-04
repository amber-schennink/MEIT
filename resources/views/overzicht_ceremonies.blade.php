<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    @include('partials.flash')

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
                <a class="hover:underline underline-offset-2 flex items-center" href="{{url('deelnemers/' . $deelnemer->id)}}">
                  <img src="{{asset('assets/user.svg')}}" /> 
                  <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
                </a>
              </div>
              <div>
                <img src="{{asset('assets/email.svg')}}" /> 
                <a class="hover:underline underline-offset-2" href="mailto: {{$deelnemer->email}}"><p>{{$deelnemer->email}}</p></a>
              </div>
              @if(isset($deelnemer->telefoon_nummer))
                <div>
                  <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                    <img src="{{asset('assets/telephone.svg')}}" /> 
                    <p>{{$deelnemer->telefoon_nummer}}</p>
                  </a>
                </div>
              @else
                <div class="!hidden lg:!flex cursor-default">
                  <div class="flex opacity-0 items-center">
                    <img src="{{asset('assets/telephone.svg')}}" /> 
                    <p>06-12345678</p>
                  </div>
                </div>
              @endif
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
                  <a class="hover:underline underline-offset-2 flex items-center" href="{{url('deelnemers/' . $deelnemer->id)}}">
                    <img src="{{asset('assets/user.svg')}}" /> 
                    <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
                  </a>
                </div>
                <div>
                  <a class="hover:underline underline-offset-2 flex items-center" href="mailto:{{$deelnemer->email}}">
                    <img src="{{asset('assets/email.svg')}}" /> 
                    <p>{{$deelnemer->email}}</p>
                  </a>
                </div>
                @if(isset($deelnemer->telefoon_nummer))
                  <div>
                    <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                      <img src="{{asset('assets/telephone.svg')}}" /> 
                      <p>{{$deelnemer->telefoon_nummer}}</p>
                    </a>
                  </div>
                @else
                  <div class="!hidden md:!flex cursor-default">
                    <div class="flex opacity-0 items-center">
                      <img src="{{asset('assets/telephone.svg')}}" /> 
                      <p>06-12345678</p>
                    </div>
                  </div>
                @endif
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
          <?php 
            $data = [];
            $data['ceremonies'] = DB::table('ceremonies')->get(); 
            $data['intakegesprekken'] = DB::table('intakegesprekken')->get(); 
            $data['mogelijkheden'] = DB::table('intake_mogelijkheden')->get(); 
            $data['trainingen'] = DB::table('trainingen')->get();

            $file = 'overzicht_ceremonies';
          ?>
          @include('partials.schema')
          <form action="{{url('gesprek_mogelijkheden')}}" method="POST" class="m-auto w-fit flex flex-col md:block mt-5 md:mt-auto">
            @csrf
            <input class="mt-3 md:mt-0" onchange="setDatum(this.value)" id="mogenlijkheid-form-datum" name="datum" type="date" required/>
            <input class="md:ml-4 md:mr-2 mt-3 md:mt-0" onchange="setTijden(this.value, document.getElementById('mogenlijkheid-form-eind-tijd').value)" id="mogenlijkheid-form-begin-tijd" name="begin_tijd" type="time" required />
            <input class="md:mr-4 md:ml-2 mt-3 md:mt-0" onchange="setTijden(document.getElementById('mogenlijkheid-form-begin-tijd').value, this.value)" id="mogenlijkheid-form-eind-tijd" name="eind_tijd" type="time" required />
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

  function setDatum(datum){
    block = document.getElementById(datum)
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden)')
    ghosts.forEach(ghost => {
      ghost.classList.add('hidden')
    });
    if(block){
      ghost_block = block.getElementsByClassName('ghost-block')[0]
      ghost_block.classList.remove('hidden')
      scrollSchemaTo(datum)
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
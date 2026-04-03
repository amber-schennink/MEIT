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

      $file_type = 'ceremonie';
    ?>
    @include('partials.nav')
    @include('partials.flash')

    
    <div class="container">
      <div id="ceremonies">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2 class="mb-3">Ceremonies</h2>
          <a href="{{url('ceremonie_form')}}"><button class="md:ml-5 mt-3 md:mt-0">Nieuwe ceremonie</button></a>
        </div>
        <div class="ceremonie-container">
          @foreach($ceremonies as $ceremonie)
            <?php 
              $datum = new DateTime($ceremonie->datum);
              if($ceremonie->id_deelnemer){
                $deelnemer = $deelnemers->where('id', '=', $ceremonie->id_deelnemer)->first();
              }else{
                $deelnemer = NULL;
              }
            ?>
            <div <?php if($datum < new DateTime('00:00:00')){echo 'class="opacity-75 order-1"';}?>>
              <div>
                <div>
                  <img src="{{asset('assets/date.svg')}}" /> 
                  <p>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}} <?php if($datum->format('Y') != date("Y")){ echo $datum->format('Y');} ?></p>
                </div>
                <div>
                  <img src="{{asset('assets/time.svg')}}" /> 
                  <p>11:00 - 16:00</p>
                </div>
              </div>
              <div>
                @if($deelnemer)
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
                  @endif
                  <div class="cursor-pointer" onclick="showPopUpUpdateBetaalStatus('{{$ceremonie->id}}', '{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}')">
                    @if($ceremonie->betaal_status == 2)
                      <p class="text-green-400 betaal-status">Betaald met betaal link</p>
                    @elseif($ceremonie->betaal_status == 1)
                      <p class="text-green-400 betaal-status">Contant betaald</p>
                    @else
                      <p class="text-orange-400 betaal-status">Deels betaald</p>
                    @endif
                  </div>
                  <div class="flex justify-between">
                    <a class="w-1/2 pr-1" onclick="showPopUpUpdateBetaalStatus('{{$ceremonie->id}}', '{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}'); event.stopPropagation();">
                      <button class="w-full !min-w-0 !bg-second/90 hover:!bg-second-dark/90 mt-3 !text-sm">Aanpassen</button>
                    </a>
                    <a class="w-1/2 pl-1" onclick="showPopUpDeelnemerVerwijderen(`{{$ceremonie->id}}`, '{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}', `{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}`); event.stopPropagation();">
                      <button class="w-full !min-w-0 !bg-red-600/90 hover:!bg-red-700/90 mt-3 !text-sm">Verwijderen</button>
                    </a>
                  </div>
                @else
                  <p>Er is nog geen aanmelding gedaan voor deze ceremonie</p>
                  <a onclick="showPopUpDeelnemerToevoegen('{{$ceremonie->id}}', `{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}`)"><button class="w-full !min-w-0 !text-sm">Deelnemer toevoegen</button></a>
                @endif
              </div>
              <a class="mt-auto" href="/ceremonie_form/{{$ceremonie->id}}"><button class="w-full mt-10">Aanpassen</button></a>
              <a onclick="showPopUpVerwijderCeremonie('{{$ceremonie->id}}')"><button class="w-full !bg-red-600/90 hover:!bg-red-700/90 mt-3">Verwijderen</button></a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  
    <div id="pop-up-verwijder-ceremonie" onclick="this.classList.add('!hidden')" class="!hidden pop-up">
      <div>
        <h4>Weet je zeker dat je deze ceremonie wilt verwijderen?</h4>
        <p>Een eventuele aanmelding wordt ook verwijderd</p>
        <div>
          <a id="afmelden" onclick="event.stopPropagation();"><button>Verwijderen</button></a>
          <button class="!cursor-pointer alt-2">Niet verwijderen</button>
        </div>
      </div>
    </div>
    <div id="pop-up-update-betaal-status" onclick="this.classList.add('!hidden')" class="!hidden pop-up">
      <div>
        <h4>Heeft <span>naam</span> betaald?</h4>
        <form action="" method="POST" class="mt-4">
          @csrf
          <input class="id_ceremonie hidden" name="id_ceremonie" readonly required/>
          <div>
            <a onclick="event.stopPropagation();"><button formaction="/ceremonieDeelnemerBetaalStatusAanpassen/1" class="bg-green-600/90!">Ja, Contant</button></a>
            <a onclick="event.stopPropagation();" class=" ml-4"><button formaction="/ceremonieDeelnemerBetaalStatusAanpassen/2" class="bg-green-600/90!">Ja, via betaal link</button></a>
          </div>
          <div class="mt-4!">
            <a onclick="event.stopPropagation();"><button formaction="/ceremonieDeelnemerBetaalStatusAanpassen/0" class="bg-second/90!">Deels</button></a>
            <button type="button" class="!cursor-pointer alt-2 ml-4">Annuleren</button>
          </div>
        </form>
      </div>
    </div>
    <div id="pop-up-deelnemer-verwijderen" onclick="this.classList.add('!hidden')" class="!hidden pop-up">
      <div>
        <h4>Weet je zeker dat je <span class="pop-up-naam">naam</span> van deze ceremonie <br> op <span class="pop-up-datum">datum</span> wilt verwijderen?</h4>
        <p>De ceremonie wordt weer opengezet voor een nieuwe deelnemer</p>
        <div>
          <a id="deelnemer-verwijderen" onclick="event.stopPropagation();"><button>Verwijderen</button></a>
          <button class="!cursor-pointer alt-2">Niet verwijderen</button>
        </div>
      </div>
    </div>
    <div id="pop-up-deelnemer-toevoegen" onclick="this.classList.add('!hidden')" class="!hidden pop-up">
      <div onclick="event.stopPropagation();">
        <form onsubmit="return checkForm()" action="" method="POST">
          @csrf
          <h4>Deelnemer toevoegen aan ceremonie op <span class="pop-up-datum">datum</span></h4>
          <br>
          <p>Kies een deelnemer</p>
          <div class="deelnemerSelect">
            @foreach($deelnemers as $deelnemerOption)
              <label>
                <p>{{$deelnemerOption->voornaam}} {{$deelnemerOption->tussenvoegsel}} {{$deelnemerOption->achternaam}}</p>
                <input class="hidden" name="deelnemerSelectie" type="radio" value="{{$deelnemerOption->id}}"></input>
              </label>
            @endforeach
          </div>
          <br>
          <p>Heeft de deelnemer al betaald?</p>
          <select name="deelnemerBetaalOptie" required>
            <option value="" selected disabled>Selecteer een optie</option>
            <option value="1">Ja, contant</option>
            <option value="2">Ja, via betaal link</option>
            <option value="0">Deels</option>
          </select>
          <div class="pop-up-buttons mt-7 flex justify-between">
            <a id="deelnemer-verwijderen"><button type="submit">Toevoegen</button></a>
            <button onclick="document.getElementById('pop-up-deelnemer-toevoegen').classList.add('!hidden')" class="!cursor-pointer alt-2" type="button">Annuleren</button>
          </div>
        </form>
      </div>
    </div>
    
    @include('partials.footer')
  </body>
</html>

<script>
  var schema_start = '<?php echo str_pad($schema_start->format('H:i'), 5, '0', STR_PAD_LEFT); ?>';
    var [uur_start, min_start] = schema_start.split(':');
  var schema_eindig = '<?php echo str_pad($schema_eindig->format('H:i'), 5, '0', STR_PAD_LEFT); ?>'; 
  var input_begin = document.getElementById('mogenlijkheid-form-begin-tijd')
  var input_eind = document.getElementById('mogenlijkheid-form-eind-tijd')
  var button = document.getElementById('mogenlijkheid-form-button')

  function setDatum(datum){
    blocks = document.getElementsByClassName(datum)
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden)')
    ghosts.forEach(ghost => {
      ghost.classList.add('hidden')
    });
    if(blocks.length > 0){
      ghost_block = blocks[0].getElementsByClassName('ghost-block')[0]
      ghost_block.classList.remove('hidden')
      ghost_block = blocks[1].getElementsByClassName('ghost-block')[0]
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

  function showPopUpVerwijderCeremonie(id) {
    pop_up = document.getElementById('pop-up-verwijder-ceremonie');
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('afmelden');

    afmelden.href = "ceremonie_verwijderen/" + id;
  }
  function showPopUpUpdateBetaalStatus(id, naam) { 
    pop_up = document.getElementById('pop-up-update-betaal-status');
    pop_up.getElementsByTagName('span')[0].innerText = naam
    pop_up.getElementsByClassName('id_ceremonie')[0].value = id
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('deelnemer-aanpassen');
  }
  function showPopUpDeelnemerVerwijderen(id, naam, datum) {
    pop_up = document.getElementById('pop-up-deelnemer-verwijderen');
    pop_up.getElementsByClassName('pop-up-naam')[0].innerText = naam 
    pop_up.getElementsByClassName('pop-up-datum')[0].innerText = datum 
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('deelnemer-verwijderen');

    afmelden.href = "ceremonie_deelnemer_verwijderen/" + id;
  }
  function showPopUpDeelnemerToevoegen(id, datum){
    pop_up = document.getElementById('pop-up-deelnemer-toevoegen')
    pop_up.classList.remove('!hidden');
    pop_up.getElementsByClassName('pop-up-datum')[0].innerHTML = datum
    form = pop_up.getElementsByTagName('form')[0]
    form.action = 'ceremonie_deelnemer_toevoegen/' + id
  }

  function checkForm(){
    var inputs = document.getElementsByTagName('input');
    radio_checked = false;
    for (let i = 0; i < inputs.length; i++) {
      if(inputs[i].type == 'radio' && inputs[i].checked){
        radio_checked = true
        break
      }
    }
    
    if(!radio_checked){
      alert('Selecteer een deelnemer')
      return false;
    }
  }
</script>
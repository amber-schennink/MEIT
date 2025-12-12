<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
  <body class="bg-main">
    @include('partials.nav')

    <?php 
      use Illuminate\Support\Facades\Config;
      $maanden = Config::get('info.maanden');
    ?>
    
    <div class="container">
      <div id="trainingen">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2>Trajecten</h2>
          <div class="flex flex-col items-center md:block">
            <a href="overzicht_export"><button>Exporteer data deelnemers</button></a>
            <a href="training_form"><button class="md:ml-5 mt-3 md:mt-0">Nieuw traject</button></a>
          </div>
        </div>
        <div class="trainingen">
          @foreach($trainingen as $key => $training)
            <div id="{{$training->id}}" <?php if(new DateTime($training->start_moment_4) < new DateTime('00:00:00')){echo 'class="opacity-75"';} ?> >
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
              @php
                $beginTijd = new DateTime($training->start_moment);
                $eindTijd = new DateTime($training->start_moment);
                $eindTijd->modify('+3 hours');
              @endphp
              <h6 class="text-main">{{$beginTijd->format('H:i')}} - {{$eindTijd->format('H:i')}}</h6>
              <?php 
                $aanmeldingen_training = $aanmeldingen->filter(fn($val) => $val->id_training == $training->id);
                $wachtlijst = [];
              ?>
              @if($aanmeldingen_training->isNotEmpty())
                <div>
                  <h6>Deelnemers:</h6>
                  @foreach($aanmeldingen_training as $aanmelding)
                    @if($aanmelding->betaal_status != 0)
                      <?php 
                        $deelnemer = $deelnemers->where('id', '=', $aanmelding->id_deelnemer)->first();
                      ?>
                      <div class="blokken">
                        <div>
                          <a class="hover:underline underline-offset-2 flex items-center" href="{{url('deelnemers/' . $deelnemer->id)}}">
                            <img src="{{asset('assets/user.svg')}}" /> 
                            <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
                          </a>
                        </div>
                        <div>
                          <a class="hover:underline underline-offset-2 flex items-center" href="mailto: {{$deelnemer->email}}">
                            <img src="{{asset('assets/email.svg')}}" /> 
                            <p class="my-3">{{$deelnemer->email}}</p>
                          </a>
                        </div>
                        @if(isset($deelnemer->telefoon_nummer))
                          <div>
                            <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                              <img src="{{asset('assets/telephone.svg')}}" /> 
                              <p>{{$deelnemer->telefoon_nummer}}</p>
                            </a>
                          </div>
                        @endif
                        @if($aanmelding->betaal_status == 2)
                          <p class="text-green-400 betaal-status">Betaald</p>
                        @else
                          <?php 
                            $deadline = new DateTime($training->start_moment);
                            $deadline->modify('-7 day');
                          ?>
                          <p class="text-orange-400 betaal-status">1 termijn betaald <br> (deadline {{$deadline->format('j')}} {{$maanden[$deadline->format('m') - 1]}})</p>
                          @if($deadline < new DateTime('00:00:00'))
                            <p class="text-red-400 betaal-status">Deadline is verstreken!</p>
                          @endif
                        @endif
                        <div class="flex justify-between">
                          <a class="w-1/2 pr-1" onclick="showPopUpDeelnemerAanpassen('{{$aanmelding->id}}', '{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}', '{{$aanmelding->betaal_status}}', '{{$training->id}}'); event.stopPropagation();">
                            <button class="w-full !min-w-0 !bg-second/90 hover:!bg-second-dark/90 mt-3 !text-sm">Aanpassen</button>
                          </a>
                          <?php $datum = new DateTime($training->start_moment) ?>
                          <a class="w-1/2 pl-1" onclick="showPopUpDeelnemerVerwijderen(`{{$aanmelding->id}}`, `{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}`, `{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}} {{$datum->format('Y')}}`); event.stopPropagation();">
                            <button class="w-full !min-w-0 !bg-red-600/90 hover:!bg-red-700/90 mt-3 !text-sm">Verwijderen</button>
                          </a>
                        </div>
                      </div>
                    @else
                      <?php 
                        $wachtlijst[] = $aanmelding
                      ?>
                    @endif
                  @endforeach
                </div>
              @else
              <h6>Er zijn nog geen aanmeldingen gedaan voor dit traject</h6>
              @endif
              @if($wachtlijst)
                <h6>Wachtlijst:</h6>
                @foreach($wachtlijst as $aanmelding)
                  <?php $deelnemer = $deelnemers->filter(fn($val) => $val->id == $aanmelding->id_deelnemer)->first() ?>
                  <div class="blokken">
                    <div>
                      <a class="hover:underline underline-offset-2 flex items-center" href="{{url('deelnemers/' . $deelnemer->id)}}">
                        <img src="{{asset('assets/user.svg')}}" /> 
                        <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
                      </a>
                    </div>
                    <div>
                      <a class="hover:underline underline-offset-2 flex items-center" href="mailto: {{$deelnemer->email}}">
                        <img src="{{asset('assets/email.svg')}}" /> 
                        <p class="my-3">{{$deelnemer->email}}</p>
                      </a>
                    </div>
                    @if(isset($deelnemer->telefoon_nummer))
                      <div>
                        <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                          <img src="{{asset('assets/telephone.svg')}}" /> 
                          <p>{{$deelnemer->telefoon_nummer}}</p>
                        </a>
                      </div>
                    @endif
                    <p class="text-red-400  betaal-status">Niet betaald</p>
                    <div class="flex justify-between">
                      <a class="w-1/2 pr-1" onclick="showPopUpDeelnemerAanpassen('{{$aanmelding->id}}', '{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}', '0', '{{$training->id}}'); event.stopPropagation();">
                        <button class="w-full !min-w-0 !bg-second/90 hover:!bg-second-dark/90 mt-3 !text-sm">Aanpassen</button>
                      </a>
                      <?php $datum = new DateTime($training->start_moment) ?>
                      <a class="w-1/2 pl-1" onclick="showPopUpDeelnemerVerwijderen(`{{$aanmelding->id}}`, `{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}`, `{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}} {{$datum->format('Y')}}`); event.stopPropagation();">
                        <button class="w-full !min-w-0 !bg-red-600/90 hover:!bg-red-700/90 mt-3 !text-sm">Verwijderen</button>
                      </a>
                    </div>
                  </div>
                @endforeach
              @endif
              <div class="mt-auto">
                @if(new DateTime($training->start_moment_4) < new DateTime('00:00:00'))
                  <button class="w-full mt-10 uit">Aanpassen</button>
                @else
                  <a href="training_form/{{$training->id}}"><button class="w-full mt-10">Aanpassen</button></a>
                @endif
                <a onclick="showPopUp('{{$training->id}}')"><button class="w-full !bg-red-600/90 hover:!bg-red-700/90 mt-3">Verwijderen</button></a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div id="pop-up" onclick="this.classList.add('!hidden')" class="!hidden">
        <div>
          <h4>Weet je zeker dat je dit traject wilt verwijderen?</h4>
          <p>Alle aanmeldingen zullen ook worden verwijderd</p>
          <div>
            <a id="afmelden" onclick="event.stopPropagation();"><button>Verwijderen</button></a>
            <button class="!cursor-pointer alt-2">Niet verwijderen</button>
          </div>
        </div>
      </div>
      <div id="pop-up-deelnemer-aanpassen" onclick="this.classList.add('!hidden')" class="!hidden pop-up">
        <div onclick="event.stopPropagation();">
          <h4>Naam</h4>
          <form method="POST" class="mt-4">
            @csrf
            <input class="id_aanmelding hidden" name="id_aanmelding" readonly required/>
            <p class="mb-1">Betaal Status</p>
            <select class="betaal_status" name="betaal_status">
              <option value="2">Betaald</option>
              <option value="1">1 termijn betaald</option>
              <option value="0">Niet Betaald</option>
            </select>
            <p class="mt-4 mb-1">Traject (start datum)</p>
            <select class="training" name="id_training">
              <?php foreach ($trainingen as $k => $t) {
                $datum = new DateTime($t->start_moment);
                echo '<option value="'. $t->id .'">'. $datum->format('j') .' '. $maanden[$datum->format('m') - 1] .' '. $datum->format('Y') .'</option>';
              } ?>
            </select>
            <div class="mt-5">
              <a id="deelnemer-aanpassen" class="mr-4"><button>Aanpassen</button></a>
              <button onclick="document.getElementById('pop-up-deelnemer-aanpassen').classList.add('!hidden')" type="button" class="!cursor-pointer alt-2" class="ml-4">Niet aanpassen</button>
            </div>
          </form>
        </div>
      </div>
      <div id="pop-up-deelnemer-verwijderen" onclick="this.classList.add('!hidden')" class="!hidden pop-up">
        <div>
          <h4>Weet je zeker dat je deze aanmelding wilt verwijderen?</h4>
          <p>Naam Startdatum</p>
          <div>
            <a id="deelnemer-verwijderen" onclick="event.stopPropagation();"><button>Verwijderen</button></a>
            <button class="!cursor-pointer alt-2">Niet verwijderen</button>
          </div>
        </div>
      </div>
    </div>
    
    @include('partials.footer')
  </body>
</html>

<script>
  function showPopUp(id) {
    pop_up = document.getElementById('pop-up');
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('afmelden');

    afmelden.href = "training_verwijderen/" + id;
  }
  function showPopUpDeelnemerAanpassen(id, naam, betaal_status, id_training) {
    pop_up = document.getElementById('pop-up-deelnemer-aanpassen');
    pop_up.getElementsByTagName('h4')[0].innerText = naam
    pop_up.getElementsByClassName('id_aanmelding')[0].value = id
    pop_up.getElementsByClassName('betaal_status')[0].value = betaal_status
    pop_up.getElementsByClassName('training')[0].value = id_training
    pop_up.getElementsByTagName('form')[0].action = 'aanmeldingDeelnemerAanpassen/' + id
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('deelnemer-aanpassen');

    afmelden.href = "training_verwijderen/" + id;
  }
  function showPopUpDeelnemerVerwijderen(id, naam, datum) {
    pop_up = document.getElementById('pop-up-deelnemer-verwijderen');
    pop_up.getElementsByTagName('p')[0].innerText = naam + ", " + datum
    pop_up.classList.remove('!hidden');
    afmelden = document.getElementById('deelnemer-verwijderen');

    afmelden.href = "afmeldenDeelnemer/" + id;
  }
</script>
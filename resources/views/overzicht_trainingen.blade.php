<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'] ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div id="trainingen">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2>Trainingen</h2>
          <div>
            <a href="overzicht_export"><button>Exporteer data deelnemers</button></a>
            <a href="training_form"><button class="ml-5">Nieuwe training</button></a>
          </div>
        </div>
        <div class="trainingen">
          @foreach($trainingen as $key => $training)
            <div id="{{$training->id}}" <?php if(new DateTime($training->start_moment_4) < new DateTime()){echo 'class="opacity-75"';} ?> >
              <div class="datums">
                @foreach($training as $key => $val)
                  @if(str_contains($key, 'start_moment'))
                    <?php
                      $datetime = new DateTime($val);
                      $maand = $datetime->format('m') - 1;
                    ?>
                    <div>
                      <p>{{ltrim($datetime->format('d'), '0')}}</p>
                      <p>{{substr($maanden[$maand], 0, 3)}}</p>
                    </div>
                  @endif
                @endforeach
              </div>
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
                          <img src="{{asset('assets/user.svg')}}" /> 
                          <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
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
                          <p class="text-orange-400 betaal-status">1 termijn betaald <br> (deadline {{ltrim($deadline->format('d'), '0')}} {{$maanden[$deadline->format('m') - 1]}})</p>
                          @if($deadline < new DateTime())
                            <p class="text-red-400 betaal-status">Deadline is verstreken!</p>
                          @endif
                        @endif
                      </div>
                    @else
                      <?php 
                        $wachtlijst[] = $aanmelding
                      ?>
                    @endif
                  @endforeach
                </div>
              @else
              <h6>Er zijn nog geen aanmeldingen gedaan voor deze training</h6>
              @endif
              @if($wachtlijst)
                <h6>Wachtlijst:</h6>
                @foreach($wachtlijst as $aanmelding)
                  <?php $deelnemer = $deelnemers->filter(fn($val) => $val->id == $aanmelding->id_deelnemer)->first() ?>
                  <div class="blokken">
                    <div>
                      <img src="{{asset('assets/user.svg')}}" /> 
                      <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
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
                  </div>
                @endforeach
              @endif
              <div class="mt-auto">
                <a href="training_form/{{$training->id}}"><button class="w-full mt-10">Aanpassen</button></a>
                <a onclick="showPopUp('{{$training->id}}')"><button class="w-full !bg-red-600/90 hover:!bg-red-700/90 mt-3">Verwijderen</button></a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div id="pop-up" onclick="this.classList.add('!hidden')" class="!hidden">
        <div>
          <h4>Weet je zeker dat je deze training wilt verwijderen?</h4>
          <p>Alle aanmeldingen zullen ook worden verwijderd</p>
          <div>
            <a id="afmelden" onclick="event.stopPropagation();"><button>Verwijderen</button></a>
            <button class="!cursor-pointer alt-2">Niet verwijderen</button>
          </div>
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
</script>
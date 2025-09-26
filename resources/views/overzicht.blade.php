<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')

    <?php 
    
      $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
      $datum = new DateTime();
      $datum->modify('-7 days');
      $aanmeldingen_afgelopen_week = $aanmeldingen->where('created_at', '>=', $datum->format('Y-m-d'));
    
      $aanmeldingen_gesorteerd = [];
      foreach($aanmeldingen_afgelopen_week as $aanmelding){
        $aanmeldingen_gesorteerd[$aanmelding->id_training][] = $aanmelding;
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
            <h4 class="my-2">Er zijn {{$aanmeldingen_afgelopen_week->count()}} nieuwe aanmeldingen sinds vorige week</h4>
            <div class="trainingen">
              @foreach($aanmeldingen_gesorteerd as $key => $aanmeldingen)
                <?php 
                  $training = $trainingen->where('id', '=', $key)->first();
                ?>
                <div class="cursor-pointer !justify-start" onclick="location.href=`{{url('trainingen')}}`">

                  <div class="datums">
                    @foreach($training as $key => $val)
                      @if(str_contains($key, 'start_moment'))
                        <?php
                          $datetime = new DateTime($val);
                          $maand = $datetime->format('m') - 1;
                        ?>
                        <div class="">
                          <p>{{ltrim($datetime->format('d'), '0')}}</p>
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
                    <p class="mx-auto text-main font-semibold mt-6">{{$created->format('d-m-Y')}}</p>
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
                        <p class="text-orange-400 betaal-status">In termijnen <br> (deadline {{ltrim($deadline->format('d'), '0')}} {{$maanden[$deadline->format('m') - 1]}})</p>
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
            <h4 class="my-2">Er zijn nog geen nieuwe aanmeldingen sinds vorige week</h4>
          @endif
        </div>
      </div>
      <!-- <div id="ceremonies">
        <h2 class="mb-3 mt-20">Ceremonies</h2>
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
            @foreach($intakegespreken as $intakegesprek)
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
            <h4 class="text-second">Intake mogenlijkheden</h4>
            <button class="mb-3 mt-6 ml-auto">Beheer intake mogenlijkheden</button>
          </div>
          <div>
              <div>
                <p>tijd - tijd</p>
              </div>
          </div>
        </div>
      </div> -->
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
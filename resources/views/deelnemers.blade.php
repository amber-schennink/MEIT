<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('partials.nav')
    @include('partials.flash')

    <?php 
      use Illuminate\Support\Facades\Config;
      use Illuminate\Support\Facades\DB;

      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');
    ?>
    
    <div class="container">
      <div>
        <h2 class="mb-3">Deelnemers</h2>
        <div class="trainingen">
          @foreach($deelnemers as $deelnemer)
            <div class="cursor-pointer" onclick="location.href = `{{url('deelnemers/' . $deelnemer->id)}}`">
              <h4 class="!text-main font-bold text-center">{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</h4>
              <div onclick="event.stopPropagation();" class="blokken cursor-default">
                <div>
                  <a class="hover:underline underline-offset-2 flex items-center" href="mailto: {{$deelnemer->email}}">
                    <img src="{{asset('assets/email.svg')}}" /> 
                    <p class="my-3">{{$deelnemer->email}}</p>
                  </a>
                </div>
                @if(isset($deelnemer->telefoon_nummer))
                  <div class="!mb-4">
                    <a class="hover:underline underline-offset-2 flex items-center" href="tel:{{$deelnemer->telefoon_nummer}}">
                      <img src="{{asset('assets/telephone.svg')}}" /> 
                      <p>{{$deelnemer->telefoon_nummer}}</p>
                    </a>
                  </div>
                @endif
                @if(isset($deelnemer->geboorte_datum) || isset($deelnemer->geboorte_tijd) || isset($deelnemer->geboorte_plaats))
                  <p>Geboorte info</p>
                @endif
                @if(isset($deelnemer->geboorte_datum))
                  <div class="flex items-center">
                    <img src="{{asset('assets/date.svg')}}" /> 
                    <?php 
                      $datetime = null;
                      $datetime = new DateTime($deelnemer->geboorte_datum);
                    ?>
                    <p>{{$datetime->format('j')}} {{$maanden[$datetime->format('m') - 1]}} {{$datetime->format('Y')}}</p>
                  </div>
                @endif
                @if(isset($deelnemer->geboorte_tijd))
                  <div class="flex items-center">
                    <img src="{{asset('assets/time.svg')}}" /> 
                    <?php 
                      $datetime = null;
                      $datetime = new DateTime($deelnemer->geboorte_tijd);
                    ?>
                    <p>{{$datetime->format('H:i')}}</p>
                  </div>
                @endif
                @if(isset($deelnemer->geboorte_plaats))
                  <div class="flex items-center">
                    <img src="{{asset('assets/location.svg')}}" /> 
                    <p>{{$deelnemer->geboorte_plaats}}</p>
                  </div>
                @endif
              </div>
              <div class="grid grid-cols-2 gap-3 text-center">
                <div onclick="event.stopPropagation(); location.href = `{{url('deelnemers/' . $deelnemer->id . '#trainingen')}}`" class="blokken !bg-trainingen w-full">
                  <p>Trajecten:</p> 
                  <p>{{$aanmeldingen->where('id_deelnemer', '=', $deelnemer->id)->count()}}</p>
                </div>
                <div onclick="event.stopPropagation(); location.href = `{{url('deelnemers/' . $deelnemer->id . '#wachtlijst')}}`" class="blokken !bg-trainingen/50 w-full">
                  <p>Op wachtlijst: </p> 
                  <p>{{$wachtlijst->where('id_deelnemer', '=', $deelnemer->id)->count()}}</p>
                </div>
                <div onclick="event.stopPropagation(); location.href = `{{url('deelnemers/' . $deelnemer->id . '#ceremonie')}}`" class="blokken !bg-ceremonies w-full">
                  <p>Ceremonies: </p> 
                  <p>{{$ceremonies->where('id_deelnemer', '=', $deelnemer->id)->count()}}</p>
                </div>
                <div onclick="event.stopPropagation(); location.href = `{{url('deelnemers/' . $deelnemer->id . '#intakegesprek')}}`" class="blokken !bg-intakegesprekken w-full">
                  <p>Tel intake: </p> 
                  <p>{{$intakegesprekken->where('id_deelnemer', '=', $deelnemer->id)->count()}}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    
    @include('partials.footer')
  </body>
</html>
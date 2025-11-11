<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body>
    @include('partials.nav')
    
    <div class="container">
      <h4 class="w-fit m-auto mb-5"><a href="{{url('login')}}" class="hover:underline underline-offset-2 text-second">Log in</a> om jouw plek voor het <a href="{{url('trainingen')}}" class="hover:underline underline-offset-2 text-second">MEIT. Traject</a> te reserveren</h4>
      <div class="flex flex-col md:flex-row gap-5 justify-center items-center">
        <a class="w-fit" href="{{url('trainingen')}}"><button>Trajecten</button></a>
        <a class="w-fit" href="{{url('ceremonies')}}"><button>Ceremonies</button></a>
        @if(session('login'))
          <a class="w-fit" href="{{url('overzicht')}}"><button>Overzicht</button></a>
        @else
          <a class="w-fit" href="{{url('login')}}"><button>Login</button></a>
        @endif
      </div>
    </div>

    @include('partials.footer')
  </body>
</html>

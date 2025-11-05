<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body>
    @include('partials.nav')
    
    <div class="container">
      <div class="flex flex-col gap-5 justify-center items-center">
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

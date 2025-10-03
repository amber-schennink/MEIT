<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div class="flex flex-col gap-5 justify-center items-center">
        <a class="w-fit" href="{{url('trainingen')}}"><button>Trainingen</button></a>
        <a class="w-fit" href="{{url('ceremonies')}}"><button>Ceremonies</button></a>
        @if(session('login'))
          <a class="w-fit" href="{{url('overzicht')}}"><button>Overzicht</button></a>
        @else
          <a class="w-fit" href="{{url('login')}}"><button>Login</button></a>
        @endif
      </div>
    </div>

  </body>
</html>

<div class="h-[400px] p-10 bg-[url('../../public/assets/meittraject.png')] bg-cover" style="background-position: 50% 20%;">
  <nav class="w-[70%] mx-auto flex gap-5">
    <a href="{{url('/')}}"><img class="h-32" src="{{asset('assets/logo.svg')}}" /></a>
    <a class="ml-auto h-fit" href="{{url('trainingen')}}"><p>Trainingen</p></a>
    @if(session('login'))
      <a class="h-fit" href="{{url('overzicht')}}"><p>Overzicht</p></a>
      <a class="h-fit" href="{{url('logout')}}"><p>Log out</p></a>
    @else
      <a class="h-fit" href="{{url('login')}}"><p>Log in</p></a>
    @endif
  </nav>
</div>
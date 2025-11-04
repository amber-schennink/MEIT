<div class="relative h-[400px] p-10 bg-[url('../../public/assets/meittraject.png')] bg-cover" style="background-position: 50% 20%;">
  <div class="hamburger relative z-40 md:hidden ml-auto w-fit" onclick="this.parentElement.classList.toggle('hamburger-active')">
    <div class="bar1"></div>
    <div class="bar2"></div>
    <div class="bar3"></div>
  </div>
  <nav>
    <a href="{{url('/')}}"><img class="h-32" src="{{asset('assets/logo.svg')}}" /></a>
    <a class="md:ml-auto h-fit" href="{{url('trainingen')}}"><p>Trajecten</p></a>
    <a class="h-fit" href="{{url('ceremonies')}}"><p>Ceremonies</p></a>
    @if(session('login'))
      @if(session('admin'))
        <a class="h-fit" href="{{url('deelnemers')}}"><p>Deelnemers</p></a>
      @endif
      <a class="h-fit" href="{{url('overzicht')}}"><p>Overzicht</p></a>
      <a class="h-fit" href="{{url('logout')}}"><p>Log out</p></a>
    @else
      <a class="h-fit" href="{{url('login')}}"><p>Log in</p></a>
    @endif
  </nav>
</div>
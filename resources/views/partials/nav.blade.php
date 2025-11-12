@if(isset($file_type) && $file_type == 'overzicht')
<div class="relative h-[400px] text-black p-10 bg-[url('../../public/assets/meit_foto_lach.jpg')] bg-cover" style="background-position: 50% 50%;">
@elseif(isset($file_type) && $file_type == 'ceremonie')
<div class="relative h-[400px] text-black p-10 bg-[url('../../public/assets/meit_foto_stoel.jpg')] bg-cover" style="background-position: 50% 15%;">
@else
<div class="relative h-[400px] text-black p-10 bg-[url('../../public/assets/meit_foto_boek.jpg')] bg-cover" style="background-position: 50% 40%;">
@endif
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
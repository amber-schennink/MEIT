<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    
    @if($errors->any())
    <div class="bg-red-600">
      <h4>{{$errors->first()}}</h4>
    </div>
    @endif
    <?php
      if(isset($_GET['training'])){
        session(['training' => $_GET['training']]);
      }
    ?>
    <form action="{{url('login')}}" method="POST" class="m-auto w-fit flex flex-col gap-4 my-8 font-semibold">
      @csrf
      <p>Email</p>
      <input type="email" name="email" required/>
      <p>Wachtwoord</p>
      <input type="password" name="wachtwoord" required/>
      <button class="mt-5" type="submit">Log in</button>
    </form>

  </body>
</html>

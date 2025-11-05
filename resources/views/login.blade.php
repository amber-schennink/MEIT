<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('partials.nav')
    @include('partials.flash')
    
    <?php
      if(isset($_GET['training'])){
        session(['training' => $_GET['training']]);
      }
    ?>
    <form action="{{url('login')}}" method="POST" class="m-auto w-fit flex flex-col gap-4 my-8 font-semibold px-4">
      @csrf
      @include('form_info_deelnemer')
      <script>switchToLogin()</script>
      <button id="login_button" class="mt-5" type="submit">Log in</button>
    </form>

    @include('partials.footer')
  </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    <?php
      use Illuminate\Support\Facades\DB; 

      $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
      $prijs = 444;

      if(!session('login') || session('admin') != true){
        redirect(url('/'));
        die();
      }
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div id="error" class="fixed top-5 left-0 right-0 w-fit m-auto bg-red-600 rounded-xl p-4 transition duration-500 opacity-0">
        <h4></h4>
      </div>
      <h2 class="mb-8">Ceremonie inplannen</h2>
      <form onsubmit="return checkForm()" action="{{url('ceremonies')}}" method="POST">
        @csrf
        <input name="first_name" type="text" class="hidden">

        <input id="id_intakegesprek" name="id_intakegesprek" value="{{$intakegesprek->id}}" class="hidden"/>
        <input id="id_deelnemer" name="id_deelnemer" value="{{$deelnemer->id}}" class="hidden"/>
        <h3>Info deelnemer</h3>
        <div class="my-8 font-semibold flex flex-col">
          <p>Naam</p>
          <p>{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
          <p class="mt-4">E-mail</p>
          <p>{{$deelnemer->email}}</p>
        </div>

        <input name="datum" type="date" required/>
        
        <br>
        <br>
        <button type="submit">Opslaan</button>
      </form>
    </div>

  </body>
</html>
<script>
  function removeBorder(){
    
  }
  function checkForm(){
    
  }
</script>

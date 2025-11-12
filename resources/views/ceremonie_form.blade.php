<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
  <body class="bg-main">
    @include('partials.nav')
    <?php
      use Illuminate\Support\Facades\Config;
      use Illuminate\Support\Facades\DB;

      $maanden = Config::get('info.maanden');
      $prijs = Config::get('info.prijs');
      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');

      if(!session('login') || session('admin') != true){
        redirect(url('/'));
        die();
      }
    ?>
    
    <div class="container">
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
        <div class="my-8 flex flex-col">
          <p>Naam</p>
          <p class="font-semibold">{{$deelnemer->voornaam}} {{$deelnemer->tussenvoegsel}} {{$deelnemer->achternaam}}</p>
          <p class="mt-4">E-mail</p>
          <p class="font-semibold">{{$deelnemer->email}}</p>
        </div>

        <input onchange="setGhost(this.value)" name="datum" type="date" required/>
        
        <br>
        <br>
        <button type="submit">Opslaan</button>
      </form>
      <div class="my-10">
        <?php 
          $data = [];
          $data['ceremonies'] = DB::table('ceremonies')->get(); 
          $data['intakegesprekken'] = DB::table('intakegesprekken')->get(); 
          $data['mogelijkheden'] = DB::table('intake_mogelijkheden')->get(); 
          $data['trainingen'] = DB::table('trainingen')->get();

          $file = 'ceremonie_form';
        ?>
        @include('partials.schema')
      </div>
    </div>

    @include('partials.footer')
  </body>
</html>
<style>
  .\!bg-intakegesprekken, .before\:bg-intakegesprekken::before, 
  .\!bg-mogelijkheden, .before\:bg-mogelijkheden::before, 
  .\!bg-ceremonies, .before\:bg-ceremonies::before, 
  .\!bg-trainingen, .before\:bg-trainingen::before{
    opacity: 70%;
  }
  .ghost-block{
    background-color: var(--color-ceremonies) !important;
  }
</style>
<script>
  var schema_start = '<?php echo str_pad($schema_start->format('H:i'), 5, '0', STR_PAD_LEFT); ?>';
  var [uur_start, min_start] = schema_start.split(':');
  var schema_eindig = '<?php echo str_pad($schema_eindig->format('H:i'), 5, '0', STR_PAD_LEFT); ?>';
  var [uur_eindig, min_eindig] = schema_eindig.split(':');

  function setGhost(datum){
    blocks = document.getElementsByClassName(datum)
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden)')
    ghosts.forEach(ghost => {
      ghost.classList.add('hidden')
    });
    for (let i = 0; i < 2; i++) {
      if(blocks[i]){
        ghost_block = blocks[i].getElementsByClassName('ghost-block')[0]
        ghost_block.classList.remove('hidden')
        scrollSchemaTo(datum)
        
        duur_uur = uur_eindig - 11
        duur_min = min_eindig - 0
        if(duur_min < 0){
          duur_min = 60 + duur_min
          duur_uur--
        }
        ghost_block.style.height = duur_uur * 50 + (duur_min / 60) * 50 + 'px'
        
      
        m_top = (11 - uur_start) * 50 + ((0 - min_start) / 60) * 50;
        ghost_block.style.marginTop = m_top + "px";
      }
    }
  }
</script>

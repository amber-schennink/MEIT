<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    <?php
      use Illuminate\Support\Facades\Config;
      use Illuminate\Support\Facades\DB;

      $maanden = Config::get('info.maanden');
      $prijs = Config::get('info.prijs');
      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');

      if(!session('login') || session('admin') != true){
        redirect(url('/'));
        die();
      }
      $edit = false;
      if(isset($training) && $training){
        $edit = true;
        $moment = [
          1 => new DateTime($training->start_moment),
          2 => new DateTime($training->start_moment_2),
          3 => new DateTime($training->start_moment_3),
          4 => new DateTime($training->start_moment_4)
        ];
      }
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <div id="error" class="fixed top-5 left-0 right-0 w-fit m-auto bg-red-600 rounded-xl p-4 transition duration-500 opacity-0">
        <h4></h4>
      </div>
      @if($edit)
        <h2 class="mb-8">Training aanpassen</h2>
        <form onsubmit="return checkForm()" action="{{url('training/'.$training->id)}}" method="POST">
      @else
        <h2 class="mb-8">Nieuwe training</h2>
        <form onsubmit="return checkForm()" action="{{url('training')}}" method="POST">
      @endif
        @csrf
        <input name="first_name" type="text" class="hidden">

        @for($i = 1; $i <= 4; $i++)
          <?php
            if($edit){
              $dag = $moment[$i]->format('Y-m-d');
              $begin_tijd = $moment[$i]->format('H:i:s');
              $moment[$i]->modify('+3 hour');
              $eind_tijd = $moment[$i]->format('H:i:s');
            }
          ?>
          <h3 class="mt-5">Week {{$i}}</h4>
          <div class="flex flex-col md:flex-row gap-4 mt-1">
            <label class="flex-1">
              <p>Dag</p>
              <input id="dag_{{$i}}" name="dag_{{$i}}" class="w-full mt-1" type="date" required <?php if($edit){echo "value='" . $dag . "'";}  ?> onchange="setGhost({{$i}})"/>
            </label>
            <label class="flex-1">
              <p>Start tijd</p>
              <input id="begin_tijd_{{$i}}" name="begin_tijd_{{$i}}" onchange="setTijd(this); setGhost({{$i}})" class="w-full mt-1" type="time" required <?php if($edit){echo "value='" . $begin_tijd . "'"; } ?>/>
            </label>
            <label class="flex-1">
              <p>Eind tijd</p>
              <input id="eind_tijd_{{$i}}" class="w-full mt-1" type="time" readonly <?php if($edit){echo "value='" . $eind_tijd . "'"; } ?>/>
            </label>
          </div>
          @endfor
        
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

          $file = 'training_form';
        ?>
        @include('partials.schema')
      </div>

    </div>

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
    background-color: var(--color-trainingen) !important;
  }
</style>
<script>
  var schema_start = '<?php echo str_pad($schema_start->format('H:i'), 5, '0', STR_PAD_LEFT); ?>';
    var [uur_start, min_start] = schema_start.split(':');
  var schema_eindig = '<?php echo str_pad($schema_eindig->format('H:i'), 5, '0', STR_PAD_LEFT); ?>'; 

  var edit = <?php if($edit){echo 'true';}else{echo 'false';}; ?>;

  if(edit){
    for (let i = 1; i <= 4; i++) {
      setGhost(i)
    }
  }

  datum = new Date();
  datum.setDate(parseInt(datum.getDate()) + 7);
  scrollSchemaTo(datum)

  function setGhost(id){
    datum = document.getElementById('dag_' + id).value;
    tijd = document.getElementById('begin_tijd_' + id).value;

    block = document.getElementById(datum)
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden).ghost-' + id)
    ghosts.forEach(ghost => {
      ghost.classList.add('hidden')
    });
    if(block){
      ghost_block = block.getElementsByClassName('ghost-block ghost-' + id)[0]
      ghost_block.classList.remove('hidden')
      scrollSchemaTo(datum)

      if(tijd){
        var [uur_begin, min_begin] = tijd.split(':');
        tijd = uur_begin + ':' + min_begin
        eindtijd = (parseInt(uur_begin) + 3) + ':'  + min_begin
        
        if(eindtijd > schema_eindig){
          ghost_block.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = '00:00'
          ghost_block.getElementsByClassName('ghost-eind-tijd')[0].innerHTML = '00:00'
          ghost_block.style.marginTop = "0px";
          return
        }

        ghost_block.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = tijd
        ghost_block.getElementsByClassName('ghost-eind-tijd')[0].innerHTML = eindtijd
      
        m_top = (uur_begin - uur_start) * 50 + ((min_begin - min_start) / 60) * 50;
        ghost_block.style.marginTop = m_top + "px";
      }
    
    }
  }

  function setTijd(val){
    var id = val.id.split("_").pop();
    var [uur, min] = val.value.split(":");
    uur = parseInt(uur) + 3
    if(uur > 23){
      uur = uur - 24;
    }
    uur = String(uur).padStart(2,"0")
    document.getElementById('eind_tijd_'+ id).value = uur + ':' + min;
  }
  function removeBorder(){
    ww.style.border = ''; 
    wwb.style.border = ''
  }
  function checkForm(){
    var dag_1 = document.getElementById('dag_1');
    var dag_2 = document.getElementById('dag_2');
    var dag_3 = document.getElementById('dag_3');
    var dag_4 = document.getElementById('dag_4');
    var error = document.getElementById('error');
    if(dag_1.value > dag_2.value || dag_2.value > dag_3.value || dag_3.value > dag_4.value){
      error.getElementsByTagName('h4')[0].innerHTML = "De datums moeten opvolgend zijn";
      error.classList.remove('opacity-0');
      setTimeout(function() { error.classList.add('opacity-0'); }, 5000);
      return false;
    }
    if(!edit){
      var date = new Date();
      date.setDate(parseInt(date.getDate()) + 7);
      date.setHours(0, 0, 0)
      if(date > new Date(dag_1.value)){
        error.getElementsByTagName('h4')[0].innerHTML = "Er moet minstens een week zitten tussen nu en de eerste start datum";
        error.classList.remove('opacity-0');
        setTimeout(function() { error.classList.add('opacity-0'); }, 10000);
        return false;
      }
    }
  }
</script>

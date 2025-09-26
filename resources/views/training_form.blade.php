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
              <input id="dag_{{$i}}" name="dag_{{$i}}" class="w-full mt-1" type="date" required <?php if($edit){echo "value='" . $dag . "'";} ?>/>
            </label>
            <label class="flex-1">
              <p>Start tijd</p>
              <input id="begin_tijd_{{$i}}" name="begin_tijd_{{$i}}" onchange="setTijd(this);" class="w-full mt-1" type="time" required <?php if($edit){echo "value='" . $begin_tijd . "'"; } ?>/>
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
    </div>

  </body>
</html>
<script>
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
    var date = new Date();
    date.setDate(parseInt(date.getDate()) + 7);
    if(date > new Date(dag_1.value)){
      error.getElementsByTagName('h4')[0].innerHTML = "Er moet minstens een week zitten tussen nu en de eerste start datum";
      error.classList.remove('opacity-0');
      setTimeout(function() { error.classList.add('opacity-0'); }, 10000);
      return false;
    }
  }
</script>

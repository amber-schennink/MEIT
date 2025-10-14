<div class="schema">
  <img id="schema-knop-l" onclick="scrollSchema('l')" class="-left-[5%]" src="{{asset('assets/arrow_left.svg')}}" />
  <div class="tijden pointer-events-none">
    @for($i = 0; $i <= $schema_eindig->format('H') - $schema_start->format('H'); $i++)
      <div class="flex items-center">
        <p class="w-12 min-w-12">{{$i + $schema_start->format('H')}}:{{$schema_start->format('i')}}</p>
        <div class="bg-main-light h-0.5 w-full"></div>
      </div>
    @endfor
  </div>
  <div id="scroll-container">
    @for($i = 0; $i < 10; $i++)
      <div class="schema-block" style="grid-template-rows: 24px  <?php echo ($schema_eindig->format('H') - $schema_start->format('H')) * 50 . 'px;'; ?>">
        <?php 
          $datum = new DateTime(); 
          $datum->modify('last sunday +1 day');
          $datum->modify('+'. $i . 'weeks')
        ?>
        @for($j = 1; $j <= 7; $j++)
          <?php 
            $dag_data = [];
            if(array_key_exists('ceremonies', $data)){
              $dag_data['ceremonies'] = $data['ceremonies']->where('datum', '=', $datum->format('Y-m-d')); 
            }
            if(array_key_exists('intakegesprekken', $data)){
              $dag_data['intakegesprekken'] = $data['intakegesprekken']->where('datum', '=', $datum->format('Y-m-d')); 
            }
            if(array_key_exists('mogelijkheden', $data)){
              $dag_data['mogelijkheden'] = $data['mogelijkheden']->where('datum', '=', $datum->format('Y-m-d')); 
            }
            if(array_key_exists('trainingen', $data)){
              $next_datum = new DateTime($datum->format('Y-m-d'));
              $next_datum->modify('+1 day');

              $dag_data['trainingen'] = $data['trainingen']->filter(function($t) use ($datum, $next_datum) {
                  $s1 = $t->start_moment   ?? null;
                  $s2 = $t->start_moment_2 ?? null;
                  $s3 = $t->start_moment_3 ?? null;
                  $s4 = $t->start_moment_4 ?? null;

                  $ds = $datum->format('Y-m-d H:i:s');
                  $de = $next_datum->format('Y-m-d H:i:s');

                  $in = function($ts) use ($ds,$de) {
                    return $ts && $ts >= $ds && $ts <= $de;
                  };

                  return $in($s1) || $in($s2) || $in($s3) || $in($s4);
              });

            }
          ?>
          <h6>{{$datum->format('j')}} {{$maanden[$datum->format('m') - 1]}}</h6>

          @if($file == 'overzicht_ceremonies' &&
            (!array_key_exists('mogelijkheden', $dag_data) || $dag_data['mogelijkheden']->isEmpty()) && 
            (!array_key_exists('intakegesprekken', $dag_data) || $dag_data['intakegesprekken']->isEmpty()))
            <div id="{{$datum->format('Y-m-d')}}" class="cursor-pointer" <?php echo 'onclick="setDatum(`'. $datum->format('Y-m-d') .'`)"';?>>
          @else
            <div id="{{$datum->format('Y-m-d')}}">
          @endif
            @foreach($dag_data as $key => $col)
              @foreach($col as $val)
                <?php echo setSchemaData($key, $val, $datum, $file) ?>
              @endforeach
            @endforeach
            @if($file == 'training_form')
              <div class="ghost-block ghost-1 hidden !h-[150px]">
                <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
              </div>
              <div class="ghost-block ghost-2 hidden !h-[150px]">
                <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
              </div>
              <div class="ghost-block ghost-3 hidden !h-[150px]">
                <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
              </div>
              <div class="ghost-block ghost-4 hidden !h-[150px]">
                <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
              </div>
            @elseif($file == 'ceremonie_form')
              <div class="ghost-block hidden !bg-ceremonies !opacity-100 ">
                <p>11:00 tot deelnemer naar huis gaat</p>
              </div>
            @elseif($file != 'ceremonies')
              <div class="ghost-block hidden">
                <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
              </div>
            @endif
          </div>
          <?php $datum->modify('+1 day') ?>
        @endfor
      </div>
    @endfor
  </div>
  <img id="schema-knop-r" onclick="scrollSchema('r')" class="-right-[5%]" src="{{asset('assets/arrow_right.svg')}}" />
</div>
<div class="mt-4 ml-[10%]">
  @if(array_key_exists('intakegesprekken', $data))
    <p class="before:content-[''] before:bg-intakegesprekken before:h-5 before:w-5 before:block flex gap-2">Intakegesprekken</p>
  @endif
  @if(array_key_exists('mogelijkheden', $data))
    <p class="before:content-[''] before:bg-mogelijkheden before:h-5 before:w-5 before:block flex gap-2">Intakegesprek mogelijkheden</p>
  @endif
  @if(array_key_exists('ceremonies', $data))
    <p class="before:content-[''] before:bg-ceremonies before:h-5 before:w-5 before:block flex gap-2">Ceremonies</p>
  @endif
  @if(array_key_exists('trainingen', $data))
    <p class="before:content-[''] before:bg-trainingen before:h-5 before:w-5 before:block flex gap-2">Trainingen</p>
  @endif
</div>
<script>
  function scrollSchema(side) {
    var container = document.getElementById('scroll-container')
    var knopL = document.getElementById('schema-knop-l')
    var knopR= document.getElementById('schema-knop-r')
    if(side == 'l'){
      w = container.getBoundingClientRect().width;
      container.scrollBy(-w, 0)
    }else{
      container.scrollBy(container.getBoundingClientRect().width, 0)
    }
    checkArrows(side)
  }
  function scrollSchemaTo(datum){
    if(!(datum instanceof Date)){
      datum = new Date(datum)
    }
    datum.setDate(datum.getDate() + ((datum.getDay() == 0) ? -6 : 1 - datum.getDay()));
    id = datum.getFullYear() + '-' + String(datum.getMonth() + 1).padStart(2,"0") + '-' + String(datum.getDate()).padStart(2,"0");
    if(document.getElementById(id)){
      schema = document.getElementById('scroll-container')
      schema.scrollLeft = document.getElementById(id).offsetLeft - 80
      
      if(schema.scrollLeft > document.getElementById(id).offsetLeft - 80){
        checkArrows('l')
      }else if(schema.scrollLeft < document.getElementById(id).offsetLeft - 80){
        checkArrows('r')
      }
    }
  }
  function checkArrows(side){
    var container = document.getElementById('scroll-container')
    var knopL = document.getElementById('schema-knop-l')
    var knopR= document.getElementById('schema-knop-r')
    
    if(side == 'l'){
      if(container.scrollLeft <= Math.ceil(container.getBoundingClientRect().width)){
        knopL.classList.add('uit');
      }
      knopR.classList.remove('uit');
    }else{
      if(container.scrollLeft >= (container.scrollWidth - (Math.ceil(container.getBoundingClientRect().width) * 2))){
        knopR.classList.add('uit');
      }
      knopL.classList.remove('uit');
    }
  }
</script>
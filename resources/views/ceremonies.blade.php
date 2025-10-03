<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('head')
  <body class="bg-main">
    @include('nav')
    <?php 
      $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
      $schema_start = 8;
      $schema_eindig = 20;
    ?>
    
    <div class="max-w-[68rem] mx-auto my-10 px-4 py-8">
      <h2 class="mb-3">Ceremonies</h2>
      <h3>Inschrijven intakegesprek</h3>
      <div class="schema">
        <img id="schema-knop-l" onclick="scrollSchema('l')" class="-left-[5%] uit" src="{{asset('assets/arrow_left.svg')}}" />
        <div class="tijden pointer-events-none">
          @for($i = 0; $i <= $schema_eindig-$schema_start; $i++)
            <div class="flex items-center">
              <p class="w-12 min-w-12">{{$i + $schema_start}}:00</p>
              <div class="bg-main-light h-0.5 w-full"></div>
            </div>
          @endfor
        </div>
        <div id="scroll-container">
          @for($i = 0; $i < 5; $i++)
            <div class="schema-block">
              <?php 
                $datum = new DateTime(); 
                $datum->modify('last sunday +1 day');
                $datum->modify('+'. $i . 'weeks')
              ?>
              @for($j = 1; $j <= 7; $j++)
                <?php $mogenlijkheden = $intake_mogenlijkheden->where('datum', '=', $datum->format('Y-m-d')); ?>
                <h6>{{$datum->format('d')}} {{$maanden[$datum->format('m') - 1]}}</h6>
                <div id="{{$datum->format('Y-m-d')}}" <?php if($datum < new DateTime()){echo 'style="opacity: 75%; pointer-events: none;"';};?>>
                  @foreach($mogenlijkheden as $mogenlijkheid)
                    <?php 
                      $begin_tijd = new DateTime($mogenlijkheid->begin_tijd);
                      $eind_tijd = new DateTime($mogenlijkheid->eind_tijd);
                      $duur = $begin_tijd->diff($eind_tijd);
                      $top = ($begin_tijd->format('H') - $schema_start) * 50 + ($begin_tijd->format('i') / 60) * 50;
                      $height = $duur->h * 50 + ($duur->i / 60) * 50;
                    ?>
                    <div class="relative cursor-pointer" <?php echo 'style="top: '. $top .'px;height: '. $height .'px; "'; echo 'onclick="setBlock(`'.$mogenlijkheid->id.'`); setTijden()"'; //echo 'onclick="setDatum(`'. $datum->format('Y-m-d') .'`)"';?>>
                      <div id="{{$mogenlijkheid->id}}" class="ghost-block relative !bg-[#f9b51d]/75 hidden">
                        <p><span class="ghost-begin-tijd">00:00</span> - <span class="ghost-eind-tijd">00:00</span></p>
                      </div>
                    </div>
                  @endforeach
                </div>
                <?php $datum->modify('+1 day') ?>
              @endfor
            </div>
          @endfor
        </div>
        <img id="schema-knop-r" onclick="scrollSchema('r')" class="-right-[5%]" src="{{asset('assets/arrow_right.svg')}}" />
      </div>
      <form action="{{url('intakegesprek')}}" method="POST">
        @csrf
        <div class="m-auto w-fit">
          @if(session('login') && session('id'))
            <input name="id_deelnemer" class="hidden" value="{{session('id')}}" readonly required/>
          @endif
          <input id="intakegesprek-form-id-mogenlijkheid" name="id_mogenlijkheid" class="hidden" readonly required/>
          <input onchange="setDatum(this.value)" id="intakegesprek-form-datum" name="datum" type="date" required/>
          <input class="ml-4 mr-2" onchange="setTijden(this.value)" id="intakegesprek-form-begin-tijd" name="begin_tijd" type="time" required />
          <button id="intakegesprek-form-button" class="mb-3 mt-6 ml-auto" type="submit">Inschrijven</button>
        </div>
        @if(!session('login') || !session('id'))
          @include('form_info_deelnemer')
        @endif
      </form>
    </div>

  </body>
</html>

@if($errors->any())
  <script>
    <?php echo 'alert("'.$errors->first().'");'; ?>
    document.getElementById('intakegesprek-form-datum').value ='';
    document.getElementById('intakegesprek-form-begin-tijd').value = '';
  </script>
@endif

<script>
  function scrollSchema(side) {
    var container = document.getElementById('scroll-container')
    var knopL = document.getElementById('schema-knop-l')
    var knopR= document.getElementById('schema-knop-r')
    if(side == 'l'){
      w = container.getBoundingClientRect().width;
      container.scrollBy(-w, 0)
      if(container.scrollLeft <= Math.ceil(w)){
        knopL.classList.add('uit');
      }
      knopR.classList.remove('uit');
    }else{
      container.scrollBy(container.getBoundingClientRect().width, 0)
      knopL.classList.remove('uit');
      w = container.scrollWidth - (Math.ceil(container.getBoundingClientRect().width) * 2)
      if(container.scrollLeft >= w){
        knopR.classList.add('uit');
      }
    }
  }
  const intake_mogenlijkheden = <?php echo json_encode($intake_mogenlijkheden); ?>;
  console.log(intake_mogenlijkheden);
  function setBlock(mogenlijkheid_id){
    block_data = intake_mogenlijkheden.find(obj => {
      return obj.id == mogenlijkheid_id
    })
    
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden)')
    ghosts.forEach(ghost => {
      ghost.style.marginTop = "0px";
      ghost.classList.add('hidden')
    });

    ghost_block = document.getElementById(mogenlijkheid_id);
    ghost_block.classList.remove('hidden')
    document.getElementById('intakegesprek-form-id-mogenlijkheid').value = mogenlijkheid_id;
    
    document.getElementById('intakegesprek-form-datum').value = block_data['datum']
    // setTijden()

  }
  function setDatum(datum){
    ghost_block = document.getElementById(datum).getElementsByClassName('ghost-block')[0]
    document.getElementById('intakegesprek-form-id-mogenlijkheid').value = '';
    ghosts = document.querySelectorAll('.ghost-block:not(.hidden)')
    button = document.getElementById('intakegesprek-form-button')
    button.classList.remove('uit')
    document.getElementById('intakegesprek-form-begin-tijd').value = '';

    ghosts.forEach(ghost => {
      ghost.style.marginTop = "0px";
      ghost.classList.add('hidden')
    });

    if(ghost_block){
      ghost_block.classList.remove('hidden')
      document.getElementById('intakegesprek-form-id-mogenlijkheid').value = ghost_block.id;
    }else{
      button.classList.add('uit')
    }
    
    document.getElementById('intakegesprek-form-datum').value = datum
    setTijden()
  }
  function setTijden(begintijd){
    ghost = document.querySelectorAll('.ghost-block:not(.hidden)')[0]
    if(!ghost){
      return
    }
    block_data = intake_mogenlijkheden.find(obj => {
      return obj.id == ghost.id
    })
    console.log(block_data)
    other_blocks_data = intake_mogenlijkheden.filter(obj => {
      return obj.datum == block_data.datum && obj.id != ghost.id
    })
    document.getElementById('intakegesprek-form-id-mogenlijkheid').value = ghost.id
    input_begin = document.getElementById('intakegesprek-form-begin-tijd')
    input_begin.style.border = "none"
    button = document.getElementById('intakegesprek-form-button')
    button.classList.remove('uit')

    block_start_split = block_data.begin_tijd.split(':')
    block_start = block_start_split[0] + ":" + block_start_split[1]
    block_einde_split = block_data.eind_tijd.split(':')
    block_einde = (block_einde_split[0] - 1).toString().padStart(2,"0") + ":" + block_einde_split[1]

    if(!begintijd){
      begintijd = block_start;
    }
    begintijd = begintijd.padStart(5,"0")
    
    var [uur_begin, min_begin] = begintijd.split(':');
    
    if(begintijd < block_start || begintijd > block_einde){
      data_id = null;
      other_blocks_data.forEach(data => {
        block_start_split = data.begin_tijd.split(':')
        block_start = block_start_split[0] + ":" + block_start_split[1]
        block_einde_split = data.eind_tijd.split(':')
        block_einde = (block_einde_split[0] - 1).toString().padStart(2,"0") + ":" + block_einde_split[1]
        if(begintijd >= block_start && begintijd <= block_einde){
          other_block = true;
          return data_id = data.id
        }
      });
      if(data_id){
        setBlock(data_id)
        setTijden(begintijd)
      }else{
        input_begin.style.border = "red solid 2px"
        button.classList.add('uit')
        if(ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML != "00:00"){
          ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = "00:00";
          ghost.getElementsByClassName('ghost-eind-tijd')[0].innerHTML = "00:00";
          ghost.style.marginTop = "0px";
        }
      }
    }else{
      m_top = (uur_begin - block_start_split[0]) * 50 + ((min_begin - block_start_split[1]) / 60) * 50;
      eindtijd = (parseInt(uur_begin) + 1) + ":" + min_begin
      eindtijd = eindtijd.padStart(5,"0")
      ghost.getElementsByClassName('ghost-begin-tijd')[0].innerHTML = begintijd;
      ghost.getElementsByClassName('ghost-eind-tijd')[0].innerHTML = eindtijd;
      document.getElementById('intakegesprek-form-begin-tijd').value = begintijd;
      ghost.style.marginTop = m_top  + "px";
    }

  }
</script>
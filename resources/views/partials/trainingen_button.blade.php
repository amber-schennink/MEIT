<?php
  $btnTekst = 'Aanmelden';
  $extraTekst = '';
  $betaald = false;
  $btnUit = false;
  $termijn = false;
  $deelnemer = null;
  if(!isset($deadline)){
    $deadline = new DateTime('00:00:00');
    $deadline->modify('+7 days');
  }
  if(session('id')){
    $deelnemer = $aanmeldingen->where('id_deelnemer', '=',  session('id'))->first();
  }
  if($deelnemer){
    if($deelnemer->betaal_status == 0){
      $btnTekst = 'Op wachtlijst';
      if($beschikbaar > 0){
        $extraTekst = '(Rond betaling af om je plek te garanderen)';
      }
    }elseif($deelnemer->betaal_status == 1){
      $termijn = true;
    }else{
      $btnTekst = 'Aangemeld';
      $btnUit = true;
    }
  }elseif(new DateTime($training->start_moment) < $deadline){
    $extraTekst = 'Sorry het is niet meer mogenlijke om je aan te melden voor dit traject';
    $btnUit = true;
  }elseif($beschikbaar == 0){
    $btnTekst = 'Opgeven wachtlijst';
  }
?>
@if($extraTekst)
  <p class="mb-3">{{$extraTekst}}</p>
@endif
@if($btnUit)
  <button class="uit" type="button">{{$btnTekst}}</button>
@elseif($termijn)
  <a href="{{url('/checkout/charge-remaining/' . $deelnemer->id)}}"><button>Betaal termijn</button></a>
@else 
  <a <?php echo 'href="../aanmelden/'.$training->id.'"' ?>><button>{{$btnTekst}}</button></a>
@endif
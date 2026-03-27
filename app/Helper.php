<?php 
  use Illuminate\Support\Facades\Config;

  if (!function_exists('setSchemaData')) {
    function setSchemaData($key, $val, $datum, $file){
      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      if($key == 'trainingen'){
        foreach ($val as $value) {
          if(str_contains($value, $datum->format('Y-m-d'))){
            $datum_tijd = new DateTime($value);
            $begin_tijd = new DateTime($datum_tijd->format('H:i'));
            $datum_tijd->modify('+3 hours');
            $eind_tijd = new DateTime($datum_tijd->format('H:i'));
            break;
          }
        }
      }elseif($key == 'ceremonies'){
        $begin_tijd = new DateTime('11:00');
        $eind_tijd = new DateTime('16:00');
        // $eind_tijd = new DateTime(str_pad($schema_eindig->format('H'), 2, '0', STR_PAD_LEFT) . ':00');
      }else{
        $begin_tijd = new DateTime($val->begin_tijd);
        $eind_tijd = new DateTime($val->eind_tijd);
      }
      $duur = $begin_tijd->diff($eind_tijd);
      $top = ($begin_tijd->format('H') - $schema_start->format('H')) * 50 + ($begin_tijd->format('i') / 60) * 50;
      $height = $duur->h * 50 + ($duur->i / 60) * 50;
      $return = '<div class="!bg-'. $key .'" style="top: '. $top .'px;height: '. $height .'px; ">';
        if($file == 'overzicht' && isset($val->id_deelnemer)){
          $return .= '<a href="deelnemers/'.$val->id_deelnemer.'" class="h-full block">';
        }elseif($file == 'overzicht' && $key == 'trainingen'){
          $return .= '<a href="trainingen" class="h-full block">';
        }
          $return .= '<p>' . $begin_tijd->format('H:i'); 
          $return .=' - '.$eind_tijd->format('H:i'); 
          $return .='</p>';
        if($file == 'overzicht'){
          $return .='</a>';
        }
      $return .='</div>';
      return $return;
    }
  }

?>
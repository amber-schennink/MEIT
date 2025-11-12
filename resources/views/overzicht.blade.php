<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('partials.head')
  <body class="bg-main">
    <?php 
      $file_type = 'overzicht';
    ?>
    @include('partials.nav')

    <?php

      use Illuminate\Support\Facades\DB;

      use Illuminate\Support\Facades\Config;

      $schema_start = Config::get('info.schema_start');
      $schema_eindig = Config::get('info.schema_eindig');
      $maanden = Config::get('info.maanden');

      $datum = new DateTime();
      $datum->modify('-7 days');
      $aanmeldingen_afgelopen_week = $aanmeldingen->where('created_at', '>=', $datum->format('Y-m-d'))->count();
      $ceremonies_afgelopen_week = $ceremonies->where('created_at', '>=', $datum->format('Y-m-d'))->count();
      $intakegesprekken_afgelopen_week = $intakegesprekken->where('created_at', '>=', $datum->format('Y-m-d'))->count();
    ?>
    
    <div class="container">
      <div>
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2>Overzicht</h2>
        </div>
        <div class="mt-5 mb-7 trainingen">
          <div onclick="location.href = `{{url('trainingen')}}`" class="text-center !bg-trainingen cursor-pointer">
            <p class="text-3xl">{{$aanmeldingen_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe aanmelding<?php if($aanmeldingen_afgelopen_week != 1) echo 'en'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
          <div onclick="location.href = `{{url('ceremonies')}}`" class="text-center !bg-ceremonies cursor-pointer">
            <p class="text-3xl">{{$ceremonies_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe ceremonie<?php if($ceremonies_afgelopen_week != 1) echo 's'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
          <div onclick="location.href = `{{url('ceremonies#intakegesprekken')}}`" class="text-center !bg-intakegesprekken cursor-pointer">
            <p class="text-3xl">{{$intakegesprekken_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe intakegesprek<?php if($intakegesprekken_afgelopen_week != 1) echo 'ken'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
        </div>
      </div>
      <div>
        <?php 
          $data = [];
          $data['ceremonies'] = DB::table('ceremonies')->get(); 
          $data['intakegesprekken'] = DB::table('intakegesprekken')->get(); 
          $data['mogelijkheden'] = DB::table('intake_mogelijkheden')->get(); 
          $data['trainingen'] = DB::table('trainingen')->get();

          $file = 'overzicht';
        ?>
        @include('partials.schema')
      </div>
    </div>
    
    @include('partials.footer')
  </body>
</html>
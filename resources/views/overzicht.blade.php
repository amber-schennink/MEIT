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
      $ceremonies_afgelopen_week = DB::table('ceremonies')->where([
        ['updated_at', '>=', $datum->format('Y-m-d')],
        ['id_deelnemer', '!=', 'NULL']
        ])->count();
    ?>
    
    <div class="container">
      <div>
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
          <h2>Overzicht</h2>
        </div>
        <div class="mt-5 mb-7 trainingen md:grid-cols-2!">
          <div onclick="location.href = `{{url('trainingen')}}`" class="text-center !bg-trainingen cursor-pointer">
            <p class="text-3xl">{{$aanmeldingen_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe traject aanmelding<?php if($aanmeldingen_afgelopen_week != 1) echo 'en'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
          <div onclick="location.href = `{{url('ceremonies')}}`" class="text-center !bg-ceremonies cursor-pointer">
            <p class="text-3xl">{{$ceremonies_afgelopen_week}}</p> 
            <p class="text-xl">nieuwe ceremonie aanmelding<?php if($ceremonies_afgelopen_week != 1) echo 'en'; ?></p>
            <p class="text-sm">sinds vorige week</p> 
          </div>
        </div>
      </div>
    </div>
    
    @include('partials.footer')
  </body>
</html>
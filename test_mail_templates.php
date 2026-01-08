<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\NieuweAanmelding;
use App\Mail\BevestigingAanmelding;

$testEmail = 'burak@eazyonline.nl';

echo "📧 Test emails met ECHTE database data\n\n";

// Haal echte data uit de database
$aanmelding = DB::table('aanmeldingen')
    ->where('id', 60)
    ->first();

if (!$aanmelding) {
    echo "❌ Geen aanmeldingen gevonden in de database.\n";
    exit(1);
}

$deelnemer = DB::table('deelnemers')->where('id', $aanmelding->id_deelnemer)->first();
$training = DB::table('trainingen')->where('id', $aanmelding->id_training)->first();

if (!$deelnemer || !$training) {
    echo "❌ Deelnemer of training niet gevonden.\n";
    exit(1);
}

$naam = trim($deelnemer->voornaam . ' ' . ($deelnemer->tussenvoegsel ?? '') . ' ' . $deelnemer->achternaam);

echo "Deelnemer: $naam\n";
echo "Training ID: {$training->id}\n";
echo "Start moment 1: {$training->start_moment}\n";
echo "Start moment 2: {$training->start_moment_2}\n";
echo "Start moment 3: {$training->start_moment_3}\n";
echo "Start moment 4: {$training->start_moment_4}\n";
echo "Betaalstatus: {$aanmelding->betaal_status}\n\n";

try {
    // Admin notificatie
    Mail::to($testEmail)->send(new NieuweAanmelding($deelnemer, $training, $aanmelding));
    echo "✅ Admin notificatie verstuurd\n";
    
} catch (\Exception $e) {
    echo "❌ Fout: " . $e->getMessage() . "\n";
}

echo "\n🎉 Check je inbox voor 2 emails met echte database data.\n";

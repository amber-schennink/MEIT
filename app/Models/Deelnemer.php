<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deelnemer extends Model
{
    protected $table = 'deelnemers';
    public $timestamps = false; 

    protected $fillable = [
        'voornaam',
        'tussenvoegsel',
        'achternaam',
        'email',
        'telefoon_nummer',
        'wachtwoord',
    ];
}

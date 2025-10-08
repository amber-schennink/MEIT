<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aanmelding extends Model
{
    protected $table = 'aanmeldingen';

    public $timestamps = false;

    protected $fillable = [
        'id_deelnemer',
        'id_training',
        'betaal_status',
        'created_at',
    ];

}

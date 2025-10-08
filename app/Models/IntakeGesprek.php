<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeGesprek extends Model
{
    protected $table = 'intakegesprekken';
    public $timestamps = false; 

    protected $fillable = [
        'id_deelnemer',
        'datum',
        'begin_tijd',
        'eind_tijd',
        'created_at',
    ];
}

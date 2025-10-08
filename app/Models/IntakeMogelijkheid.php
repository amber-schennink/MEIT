<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeMogelijkheid extends Model
{
    protected $table = 'intake_mogelijkheden';
    public $timestamps = false; 

    protected $fillable = [
        'datum',
        'begin_tijd',
        'eind_tijd',
        'created_at',
    ];
}

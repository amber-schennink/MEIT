<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainingen';
    public $timestamps = false;

    protected $fillable = [
        'start_moment',
        'start_moment_2',
        'start_moment_3',
        'start_moment_4',
    ];

}

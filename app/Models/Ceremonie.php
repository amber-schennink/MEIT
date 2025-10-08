<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceremonie extends Model
{
    protected $table = 'ceremonies';
    public $timestamps = false;

    protected $fillable = [
        'id_deelnemer',
        'datum',
    ];
}

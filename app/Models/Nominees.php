<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nominees extends Model
{
    /** @use HasFactory<\Database\Factories\NomineesFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'town',
        'current_votes',
    ];
}

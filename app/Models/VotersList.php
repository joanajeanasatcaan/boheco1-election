<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotersList extends Model
{
    /** @use HasFactory<\Database\Factories\VotersListFactory> */
    use HasFactory;

    protected $fillable = [
        'profile',
        'name',
        'id_number',
        'date_time_voted',
        'status',
    ];
}

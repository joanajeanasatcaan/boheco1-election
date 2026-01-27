<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictsFactory> */
    use HasFactory;

    protected $fillable = [
        'district_name',
        'nominees',
        'registered_voters',
        'votes_cast',
        'status',
    ];
}

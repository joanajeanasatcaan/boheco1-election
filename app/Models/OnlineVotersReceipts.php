<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineVotersReceipts extends Model
{
    /** @use HasFactory<\Database\Factories\OnlineVotersReceiptsFactory> */
    use HasFactory;

    protected $filable = [
        'profile',
        'name',
        'id_number',
        'date_time_voted',
        'remarks',
    ];
}

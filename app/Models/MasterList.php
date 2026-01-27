<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'district',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionSchedule extends Model
{
    protected $table = 'ECRM_election_schedules';

    protected $fillable = [
        'scheduled_date',
        'district',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date:Y-m-d', 
        'is_active'      => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoterVerification extends Model
{
    use HasFactory;
    
    protected $table = 'ECRM_VoterVerifications';
    protected $fillable = [
        'voter_id',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'voter_id', 'Id');
    }
}

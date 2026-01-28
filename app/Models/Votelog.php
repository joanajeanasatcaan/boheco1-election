<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    use HasFactory;

    protected $table = 'ECRM_VoteLogs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nominee_id',
        'member_id',
        'household_id',
        'ip_address',
    ];

    public function nominee()
    {
        return $this->belongsTo(Nominee::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'Id');
    }
}

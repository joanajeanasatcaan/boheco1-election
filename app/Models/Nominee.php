<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VoteLog;

class Nominee extends Model
{
    protected $table = 'ECRM_Nominees';
    protected $primaryKey = 'id';
    public $timestamps = true;

     protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'town',
        'district',
        'nickname',
        'image_path',
    ];

    public function votes()
    {
        return $this->hasMany(VoteLog::class);
    }

    public function votesCount()
    {
        return $this->votes()->distinct('member_id')->count('member_id');
    }
}

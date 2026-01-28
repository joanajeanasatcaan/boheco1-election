<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Member;
use App\Models\Town;
use App\Models\Barangay;
use App\Models\VoterVerification;

class MemberSpouse extends Model
{
    protected $table = 'CRM_MemberConsumerSpouse';
    protected $primaryKey = 'id';

    protected $keyType = 'string';       
    public $incrementing = false;         
    public $timestamps = true;

    protected $fillable = [
        'MemberConsumerId', 
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Gender',
        'BirthDate',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumbers',
        'EmailAddress',
    ];

    protected $appends = [
        'FullName', 
        'FullAddress'
    ];

    public function getFullNameAttribute()
    {
        return trim("{$this->FirstName} {$this->MiddleName} {$this->LastName} {$this->Suffix}");
    }

    public function getFullAddressAttribute()
    {
        $barangay = $this->barangayDetail?->Barangay;
        $town = $this->townDetail?->Town;
        $sitio = $this->Sitio;

        $parts = array_filter([$barangay, $sitio, $town]);

        return implode(', ', $parts);
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'MemberConsumerId', 'Id');
    }

    public function verification()
    {
        return $this->hasOne(
            VoterVerification::class,
            'voter_id',
            'id'
        );
    }


    public function townDetail()
    {
        return $this->belongsTo(Town::class, 'Town', 'id');
    }

    public function barangayDetail()
    {
        return $this->belongsTo(Barangay::class, 'Barangay', 'id');
    }
}

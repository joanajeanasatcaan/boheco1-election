<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barangay;
use App\Models\MemberSpouse;
use App\Models\Town;
use App\Models\Nominee;
use App\Models\Votelog;
use App\Models\VoterVerification;

class Member extends Model
{
    protected $table = 'CRM_MemberConsumers';

    protected $primaryKey = 'Id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $casts = [
        'Town' => 'integer'
    ];

    protected $fillable = [
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Gender',
        'Birthdate',
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
        $town     = $this->townDetail?->Town;
        $sitio   = $this->Sitio;

        return implode(', ', array_filter([$barangay, $sitio, $town]));
    }
    
    public function spouse()
    {
        return $this->hasOne(
            MemberSpouse::class,
            'MemberConsumerId', 
            'Id'                
        );
    }

    public function spouseOf()
    {
        return $this->hasOne(
            MemberSpouse::class,
            'SpouseConsumerId',
            'Id'
        );
    }

    public function verification()
    {
        return $this->hasOne(
            VoterVerification::class,
            'voter_id',
            'Id'
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

    public function householdId()
    {
        return $this->spouse
            ? $this->spouse->MemberConsumerId
            : $this->Id;
    }

    public function hasVotedFor(Nominee $nominee): bool
    {
        return Votelog::where('nominee_id', $nominee->id)
            ->where('household_id', $this->householdId())
            ->exists();
    }
}

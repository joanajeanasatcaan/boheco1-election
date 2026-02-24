<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcomProfile extends Model
{
    protected $table = 'ECRM_EcomProfiles';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'district',
        'ecom_password'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

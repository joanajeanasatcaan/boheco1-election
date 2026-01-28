<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $table = 'CRM_Barangays';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Barangay', 
        'TownId'
    ];

    public function town()
    {
        return $this->belongsTo(Town::class, 'TownId', 'id');
    }
}

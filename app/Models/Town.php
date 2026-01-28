<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $table = 'CRM_Towns';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'Town', 
        'District', 
        'Station'
    ];
    
    public function barangays()
    {
        return $this->hasMany(Barangay::class, 'TownId', 'id');
    }
}

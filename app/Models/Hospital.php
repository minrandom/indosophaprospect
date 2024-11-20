<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $fillable=[
        "code","name","province_id","city","city_order_no","category","address","ownership","owned_by","class","akreditas","target"

    ];

    public function prospects(){
        return $this->hasMany(Prospect::class);
    }
    public function province(){
        return $this->belongsTo(Province::class);
    }
    public function mappings(){
        return $this->hasMany(Mapping::class);
    }
    public function schedules(){
        return $this->hasMany(schedule::class);
    }
    public function validDept(){
        return $this->hasOne(DeptValidation::class);
    }
    public function vendor(){
        return $this->hasMany(deptVendorList::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable=[
        "name","prov_order_no","prov_region_code","area_code"
    ];

    public function hospitals(){
        return $this->hasMany(Hospital::class);
    }
    public function schedules(){
        return $this->hasMany(schedule::class);
    }

    public function prospects(){
        return $this->hasMany(Prospect::class);
    }

    public function area(){
        return $this->belongsTo(area::class);
    }
}

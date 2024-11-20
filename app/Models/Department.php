<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable=[
        "name", "alt_name"

    ];

    public function prospects(){
        return $this->hasMany(Prospect::class);
    }
    public function schedules(){
        return $this->hasMany(schedule::class);
    }
    public function vendor(){
        return $this->hasMany(deptVendorList::class);
    }

}

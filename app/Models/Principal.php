<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Principal extends Model
{
    use HasFactory;
    protected $fillable=[
        "name","address","contact","EOD_Terms"
    ];
  
    public function brands(){
        return $this->hasMany(Brand::class);
    }
    public function agreements(){
        return $this->hasMany(Agreement::class);
    }
}

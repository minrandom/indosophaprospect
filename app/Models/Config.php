<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    
    protected $fillable=[
        "name","config_code","category_id","unit_id","genre","brand_id","type","uom","consist_of","price_include_ppn"
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function prospects(){
        return $this->hasMany(Prospect::class);
    }

    
    public function category(){
        return $this->belongsTo(Category::class);
    }



}

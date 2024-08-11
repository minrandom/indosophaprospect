<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    
    public function configs(){
        return $this->hasMany(Config::class);
    }
    public function consumablesProspects(){
        return $this->hasMany(consumablesProspect::class);
    }

}

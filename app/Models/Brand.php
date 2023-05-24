<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable=[
        "name","country","principal_id"
    ];

    public function configs(){
        return $this->hasMany(Config::class);
    }

    public function principal(){
        return $this->belongsTo(Principal::class);
    }

}

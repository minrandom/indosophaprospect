<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class area extends Model
{
    use HasFactory;

    protected $fillable=[
        "name","detail"
    ];

    public function provinces(){
        return $this->hasMany(Config::class);
    }

    public function employee(){
        return $this->hasMany(Employee::class);
    }
}

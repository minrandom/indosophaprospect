<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable=[
        "name","head_user_id","admin_user_id","email_bu",
    ];

    public function prospects(){
        return $this->hasMany(Prospect::class);
    }

    public function configs(){
        return $this->hasMany(Config::class);
    }
    public function unitHead(){
        return $this->belongsTo(User::class, 'head_user_id');
    }

    public function unitAdmin() {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}

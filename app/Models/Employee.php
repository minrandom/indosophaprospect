<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    

    use HasFactory;
    protected $fillable=[
        "longname","sex","birthplace","birthdate","NIK","marital_status","address","position","area","join_date"

    ];

    public function user(){
        return $this->hasOne(User::class);
    }



}

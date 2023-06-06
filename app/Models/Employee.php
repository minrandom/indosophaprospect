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

    // Area : 0 -> HO ; 100-> 

    public function user(){
        return $this->hasOne(User::class);
    }

    public function area(){
        return $this->belongsTo(area::class);
    }



}

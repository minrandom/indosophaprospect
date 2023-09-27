<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    use HasFactory;
    protected $fillable=[
        "prospect_id","hospital_id","department_id","status_mapping","quetioner_link",//can be change to quetioner_id later

    ];

    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }



}

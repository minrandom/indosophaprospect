<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptValidation extends Model
{
    use HasFactory;

    protected $fillable=[
        "hospital_id","dept_valid","last_validation_by"
    ];



    public function Hospital(){
        return $this->belongsTo(Hospital::class);
    }
}

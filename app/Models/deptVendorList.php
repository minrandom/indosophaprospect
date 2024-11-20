<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deptVendorList extends Model
{
    use HasFactory;

    protected $fillable=[
        "hospital_id","dept_id","last_transaction_by"
    ];



    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_id');
    }
    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }

}

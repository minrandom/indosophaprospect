<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{
    use HasFactory;
    protected $fillable=[
       "task","hospital_id","province_id","department_id","status","created_by","create_for","validation_by","validation_at","start_time","end_time","checkin_id"
    ];

    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }
    
    public function province(){
        return $this->belongsTo(Province::class);
    }
    

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function createFor(){
        return $this->belongsTo(User::class,'create_for');
    }

    public function validator(){
        return $this->belongsTo(User::class,'validation_by');
    }



}

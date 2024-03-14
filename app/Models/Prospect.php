<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory;
    protected $fillable=[
        "user_creator",
        "pic_user_id",
        "prospect_no",
        "prospect_source",
        "province_id",
        "hospital_id",
        "department_id",
        "unit_id",
        "config_id",
        "qty",
        "submitted_price",
        "eta_po_date",
        "status",//can be change to quetioner_id later
        "validation_time",
        "validation_by",
    ];

    public function creator(){
        return $this->belongsTo(User::class,'user_creator');
    }

    public function personInCharge(){
        return $this->belongsTo(User::class,'pic_user_id');
    }

    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function province(){
        return $this->belongsTo(Province::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    //public function configall(){
      //  return $this->belongsToMany(Config::class);
   // }
    public function config(){
        return $this->belongsTo(Config::class);;
    }

    public function rejection(){
        return $this->hasOne(rejectLog::class);;
    }
    

    public function mappings(){
        return $this->hasMany(Mapping::class);
    }
    public function remarks(){
        return $this->hasMany(prospectRemarks::class);
    }

    public function review(){
        return $this->hasOne(Review::class);
    }

    public function dropRequest(){
        return $this->hasMany(DropRequest::class);
    }

    public function alerts(){
        return $this->hasMany(Alert::class);
    }

    


    
}

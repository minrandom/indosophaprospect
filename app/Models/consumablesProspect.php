<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumablesProspect extends Model
{
    use HasFactory;


    use HasFactory;
    protected $fillable=[
        "user_creator",
        "pic_user_id",
        "prospect_no",
        "tempCode",
        "prospect_source",
        "province_id",
        "hospital_id",
        "department_id",
        "unit_id",
        "category_id",
        "config_id",
        "qty",
        "submitted_total_price",
        "po_target",
        "eta_po_date",
        "status",//can be change to quetioner_id later
        "consignDrop",//can be change to quetioner_id later
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
    
    public function Cat(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }


    public function rejection(){
        return $this->belongsTo(consumablesReject::class,'prospect_no');
    }
    public function review(){
        return $this->belongsTo(consumablesReview::class);
    }
    
    public function category(){
        return $this->belongsTo(Category::class);
    }




}

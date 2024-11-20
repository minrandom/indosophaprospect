<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendorForecast extends Model
{
    use HasFactory;


    use HasFactory;
    protected $fillable=[
        "user_creator",
        "forecast_no",
        "tempCode",
        "province_id",
        "hospital_id",
        "department_id",
        "unit_id",
        "category_id",
        "config_id",
        "qty",
        "submitted_total_price",
        "po_target",
        "payment_method",
        "status",
        "result_id",
    ];
    
    public function creator(){
        return $this->belongsTo(User::class,'user_creator');
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
    public function result(){
        return $this->hasOne(vendorForecastResult::class.'result_id');
    }

}

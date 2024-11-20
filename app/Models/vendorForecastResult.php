<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendorForecastResult extends Model
{
    use HasFactory;

    use HasFactory;
    protected $fillable=[
        "user_creator",
        "forecast_id",
        "po_date",
        "result_config_id",
        "result_qty",
        "result_value",
        "comment",
     
    ];

    public function forecast(){
        return $this->belongsTo(vendorForecast::class);
    }
    
}

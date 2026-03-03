<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class installbase extends Model
{
    use HasFactory;

    protected $fillable=[
        "installbase_code","product_id","serial_number","hospital_id"
        ,"department","department_phone","equipment_location"
        ,"pic_to_recall","pic_number"
        ,"installation_date","installbase_status"
        ,"repair_status","maintenance_status","end_of_warranty"
        ,"service_contract","type_of_contract","end_of_service"
        ,"last_review","note_last_review"

    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable=[
        "validation","first_offer_date","last_offer_date","demo_date","presentation_date","user_status","purchasing_status","direksi_status","anggaran","jenis_anggaran","chance","eta_po_date","next_action","comment","set_temp","set_temp_date","set_temp_reason", "drop_request","drop_request_user_id","drop_request_date"

    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function reviewLog(){
        return $this->hasMany(ReviewLog::class);
    }

    
}

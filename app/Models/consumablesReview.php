<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumablesReview extends Model
{
    use HasFactory;
    protected $fillable=[
        "consumables_prospect_id", "first_offer_date","last_offer_date","demo_date","presentation_date","user_status","purchasing_status","direksi_status","anggaran_status","jenis_anggaran","chance","eta_po_date","next_action","comment",
 
     ];
 
     public function prospect(){
         return $this->belongsTo(consumablesProspect::class);
     }
 
     public function consumablesReviewLog(){
         return $this->hasMany(consumablesReviewLog::class);
     }
 
}

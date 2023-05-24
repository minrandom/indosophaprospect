<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;
    protected $fillable=[
        "start_date","end_date","act_as","times_extended","agreement_code","principal_id"
    ];
  
    public function principal(){
        return $this->belongsTo(Principal::class);
    }
}

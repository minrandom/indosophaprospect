<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prospectTemperature extends Model
{
    use HasFactory;
    protected $table = 'prospect_temperatures';
    protected $fillable=[
        "prospect_id","tempName",'tempCodeName','created_at','updated_at'
    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class,"prospect_id");
    }


    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rejectLog extends Model
{
    use HasFactory;
    protected $fillable=[
        "prospect_id","rejected_by","reason"
    ];


    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function validator(){
        return $this->belongsTo(User::class,'rejected_by');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class updatelog extends Model
{
    use HasFactory;
    protected $fillable=[
        "req_status","prospect_id","col_update","col_before","col_after","logdate","request_by","approve_date","approve_by"
    ];


    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function requester(){
        return $this->belongsTo(User::class,'request_by');
    }
    public function approver(){
        return $this->belongsTo(User::class,'approved_by');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class successReq extends Model
{
    use HasFactory;
    protected $fillable=[
        "prospect_id","request_date","request_reason","keterangan","request_by","validation_time","validation_by","validation_status","isBuNoted",'bu_noted_at'
    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function validation(){
        return $this->belongsTo(User::class, 'validation_by');
    }
    public function request(){
        return $this->belongsTo(User::class, 'request_by');
    }

}

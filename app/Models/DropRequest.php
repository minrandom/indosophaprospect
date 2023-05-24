<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropRequest extends Model
{
    use HasFactory;
    protected $fillable=[
        "prospect_id","request_date","request_reason","request_by","validation_date","validation_by","validation_status"
    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function validationDrop(){
        return $this->belongsTo(User::class, 'validation_by');
    }
    public function requestDrop(){
        return $this->belongsTo(User::class, 'request_by');
    }
}

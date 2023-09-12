<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewLog extends Model
{
    use HasFactory;
    protected $fillable=[
        "prospect_id","log_date","review_id","col_update","col_before","col_after","updated_by","update_at_lat","update_at_long","approve_at","approve_by"
    ];

    public function review(){
        return $this->belongsTo(Review::class);
    }

    public function reviewUpdate(){
        return $this->belongsTo(User::class, 'updated_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prospectRemarks extends Model
{
    use HasFactory;
    protected $table = 'prospect_remarks';
    protected $fillable=[
        "prospect_id","type","creator","creator_role","column_target","messages","status"
    ];

    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function creator(){
        return $this ->belongsTo(User::class);
    }



}

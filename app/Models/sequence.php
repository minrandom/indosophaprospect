<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sequence extends Model
{
    use HasFactory;
    protected $fillable=[
       "sequenceUser","sequenceData"
    ];

    public function sequnceFor(){
        return $this->belongsTo(User::class,'sequenceUser');
    }
}

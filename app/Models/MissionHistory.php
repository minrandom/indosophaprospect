<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'mission_id','actor_user_id','action','changes','note'
    ];

    protected $casts = ['changes' => 'array'];

     public function mission(){
        return $this->belongsTo(mission::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }



}

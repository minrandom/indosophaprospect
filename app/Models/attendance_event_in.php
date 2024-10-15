<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance_event_in extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'place_name',
        'address',
        'check_in_loc',
        'photo_data',
    ];

    protected $dates = [
        'check_in_at',
        'created_by',
    ];


    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }
    public function event(){
        return $this->belongsTo(Event::class,"event_id");
    }

    public function out(){
        return $this->hasOne(attendance_event_out::class,'checkin_id');
    }
}

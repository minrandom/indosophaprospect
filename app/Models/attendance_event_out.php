<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance_event_out extends Model
{
  

    use HasFactory;

    protected $fillable = [
        'checkin_id',
        'event_id',
        'user_id',
        'place_name',
        'address',
        'check_out_loc',
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

    public function attendance(){
        return $this->belongsTo(attendance_event_in::class,'checkin_id');
    }



}

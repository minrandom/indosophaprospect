<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
                'place_name',
                'address',
                'check_in_loc',
                'photo_data',
                'longitude',
                'latitude'
    ];

    protected $dates = [
        'check_in_at',
        'created_by',
    ];


    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function out(){
        return $this->hasOne(AttendanceOut::class,'checkin_id');
    }

}

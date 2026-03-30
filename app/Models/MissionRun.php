<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionRun extends Model
{
     protected $fillable = [
        'code','hospital_id','creator_id','deadline_mission',
        'status_mission','validate_mission','person_in_charge',
        'check_in_id','check_out_id'
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function picUser()
    {
        return $this->belongsTo(User::class, 'person_in_charge');
    }

    public function checkIn()
    {
        return $this->belongsTo(Attendance::class, 'check_in_id');
    }

    public function checkOut()
    {
        return $this->belongsTo(AttendanceOut::class, 'check_out_id');
    }

    public function tasks()
    {
        return $this->hasMany(mission::class, 'mission_run_id'); // your "missions" = tasks
    }

    public static function makeCode(): string
    {
        // example: MISISS-03-26-1234
        $mmYY = now()->format('m-y');
        $rand = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return "VISIT-{$mmYY}-{$rand}";
    }
}

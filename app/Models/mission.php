<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\String\s;

class mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'hospital_id',
        'department',
        'pic_user_id',
        'user_to_meet',
        'code_ref',
        'task_reference',
        'task_creator_id',
        'generate_task_via',
        'deadline',
        'priority_level',
        'expected_outcome',
        'report_result',
        'status_mission',
        'updated_by',
        'schedule_date',
        'schedule_time',
        'schedule_end_time',
    ];

    protected $casts = [
    'schedule_date' => 'date',
    'schedule_time' => 'datetime:H:i', // or keep as string via accessor; simplest:
    ];

    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }

    public function report()
    {
        return $this->hasOne(mission_report::class, 'mission_id');
    }

    public function picUser(){
        return $this->belongsTo(User::class, 'pic_user_id');
    }
    public function creator(){
        return $this->belongsTo(User::class, 'task_creator_id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function histories()
    {
        return $this->hasMany(\App\Models\MissionHistory::class);
    }
    public function missionRun()
    {
    return $this->belongsTo(\App\Models\MissionRun::class, 'mission_run_id');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO GENERATE CODE
    |--------------------------------------------------------------------------
    */

    public static function makeCode(string $taskReference): string
    {
        $map = [
            'custom' => 'CTM',
            'installbase' => 'IBM',
            'prospect' => 'PRP',
            'mapping' => 'MAP',
            'finance' => 'FIN',
            'technical' => 'TEC',
            'business_unit' => 'BUN',
            'sales_admin'=>'SAD',
        ];

        $ref = $map[$taskReference] ?? 'CTM';
        $monthYear = now()->format('m-y');
        $prefix = "TASKISS-{$monthYear}-{$ref}-";

        $lastCode = self::where('code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('code');

        $nextNumber = $lastCode
            ? ((int) substr($lastCode, -4)) + 1
            : 1;

        $number = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $prefix . $number;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO SET CODE WHEN CREATING
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($mission) {
            if (!$mission->code) {
                $mission->code = self::makeCode($mission->task_reference);
            }
        });
    }
}

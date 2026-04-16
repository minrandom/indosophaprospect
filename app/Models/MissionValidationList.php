<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionValidationList extends Model
{
    use HasFactory;
    protected $table="mission_validation_lists";

    protected $fillable = [
        'mission_id',
        'task_ref',
        'code_ref',
        'payload_form',
        'validate_by',
        'validate_at',
        'status',
    ];
}

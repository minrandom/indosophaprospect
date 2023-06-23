<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'place_name',
        'address',
        'check_in_loc',
    ];

    protected $dates = [
        'check_in_at',
        'created_by',
    ];
}

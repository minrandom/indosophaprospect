<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mission_report extends Model
{
    use HasFactory;

    protected $fillable = [
        'mission_id','reporter_user_id','payload','summary'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }
}

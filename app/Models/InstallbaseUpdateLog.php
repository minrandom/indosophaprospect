<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallbaseUpdateLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'installbase_id',
        'mission_id',
        'task_update_no',
        'field_column',
        'value_before',
        'new_value',
        'updated_by',
    ];
    public function installbase()
    {
        return $this->belongsTo(installbase::class);
    }

}

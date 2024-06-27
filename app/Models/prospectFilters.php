<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prospectFilters extends Model
{
    use HasFactory;
    protected $table = 'prospect_filters';
    protected $fillable=[
       "filterUser","filterData"
    ];

    public function filterFor(){
        return $this->belongsTo(User::class,'filterUser');
    }
}

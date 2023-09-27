<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmpProspectInput extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable=[
        "user_id",
        "source",
        "province_id",
        "hospital_id",
        "department_id",
        "unit_id",
        "category_id",
        "config_id",
        "qty",
        "review_anggaran",
        'jns_aggr',
        "eta_po_date",
        
    ];

}

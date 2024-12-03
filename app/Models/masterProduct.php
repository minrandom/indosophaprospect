<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterProduct extends Model
{
    use HasFactory;
    protected $fillable=[
        'code',"brand","modelntype",'description','category','unit_id','status','principal','factory'
    ];
  
    public function principal(){
        return $this->belongsTo(Principal::class);
    }
}

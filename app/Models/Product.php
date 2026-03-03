<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_code',"brand_id","model_type",'description','category_id','unit_id','product_status','principal_id','akl_number','akl_valid_end'
    ];

     public function installbases(){
        return $this->hasMany(installbase::class);
    }
     public function brand(){
        return $this->belongsTo(Brand::class);
    }
     public function category(){
        return $this->belongsTo(Category::class);
    }
     public function principal(){
        return $this->belongsTo(Principal::class);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use Illuminate\Support\HtmlString;
use App\Models\Hospital;
use App\Models\Config;

class DataCompileController extends Controller
{

public function getData()
 {

    $employ = Employee::with('user')->get();

    return DataTables::of($employ)
        
        
        ->addColumn('action', function($employee){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$employee->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$employee->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

             return $btn;
     })
  ->addColumn('action2', function($employee){
            
            $btn2= $employee->user->role;
            $btn2 .= ' <div class="row"><a href="javascript:void(0)" id="'.$employee->user->id.'" class="btn btn-primary btn-sm ml-2 btn-editrole">Edit Role</a>';
            $btn2 .= ' <a href="javascript:void(0)" id="'.$employee->user->id.'" class="btn btn-danger btn-sm ml-2 btn-clear">Set To User</a></div>';
            $btnx= new HtmlString($btn2);
             return $btnx;
     })

     ->addColumn('role', function ($employee) {
        return $employee->user->role ?? "N/A";
    })
    ->addColumn('userid', function ($employee) {
        return $employee->user->id ?? "N/A";
    })

         
        ->toJson();

 }


 public function HospitalData()
 {

    $prv = Hospital::with('province')->get();

    return DataTables::of($prv)
        
        
        ->addColumn('action', function($hpital){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$hpital->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$hpital->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

             return $btn;
          })
      
     ->addColumn('provname', function ($hpital) {
        return $hpital->province->name;
    })
    ->addColumn('prov_orderno', function ($hpital) {
        return $hpital->province->prov_order_no;
    })
    ->only(['id','code','name','action','category','city','address','ownership','owned_by','class','akreditas','target', 'provname', 'prov_orderno'])
    ->rawColumns(['action'])  
        ->toJson();

 }

 public function ConfigData()
 {

    $cnf = Config::with('unit','brand')->get();

    return DataTables::of($cnf)
        
        
        ->addColumn('action', function($cfg){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$cfg->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$cfg->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

             return $btn;
          })
      
     ->addColumn('bu', function ($cfg) {
        return $cfg->unit->name;
    })
    ->addColumn('brand', function ($cfg) {
        return $cfg->brand->name;
    })
    ->addColumn('pformat', function ($cfg) {
        $pformat=number_format($cfg->price_include_ppn);
        return $pformat;
    })
    //->only(['id','code','name','action','category','city','address','ownership','owned_by','class','akreditas','target', 'provname', 'prov_orderno'])
    ->rawColumns(['action'])  
        ->toJson();

 }


}

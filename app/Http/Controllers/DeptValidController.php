<?php

namespace App\Http\Controllers;

use App\Models\DeptValidation;
use App\Models\Province;
use App\Models\Hospital;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class DeptValidController extends Controller
{
    public function index()
    {
        //
    }

    public function getDeptValid($hospitalid)
    {
    // Retrieve hospitals based on the province ID
    //dd($provinceId);
    
    $deptvalid = DeptValidation::where('hospital_id', $hospitalid)->first();
    //dd($deptvalid);
    if($deptvalid!=null){
    $listdept = $deptvalid->dept_valid;
    $listdeptArray = explode(",",$listdept);

    // Ensure $listdeptArray is an array before using it in whereIn
    $deptvalid = Department::whereIn('id', $listdeptArray)->get();
    $validDeptIds = $deptvalid->pluck('id')->toArray();
    } else $validDeptIds=[0];
    
    $alldept =Department::all();
 
    
   
   
    // Iterate over all departments and add the 'stats' property
    foreach ($alldept as $dept) {
        if (in_array($dept->id, $validDeptIds)) {
            $dept->stats = 1; // If the department is in the valid list, set stats to 1
        } else {
            $dept->stats = 0; // If not, set stats to 0
        }
    }
    //dd($alldept->stats);



    return response()->json(['alldept'=>$alldept]);
    }

 
    public function create()
    {
        //
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
       
    
    
       
       // return response()->json($data);

        return view('admin.deptValidation', ['username' => $user]);
    }
    public function creation()
    {
        //
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
        $role=$user->role;
        
        $area=$user->employee->area;
        $pos=$user->employee->position;
        if($area=="HO" ){
        $provincelist=Province::all();
        }else if($role=="nsm"){
        $provincelist=Province::with('area')->where('wilayah',$area)->get();
         }else if($role==="fs"){
         $provincelist=Province::with('area')->where('prov_order_no',$area)->orWhere('iss_area_code',$area)->get();
        }else if($role==="am"){
        $provincelist=Province::with('area')->where('iss_area_code',$area)->get();}       
     
    
        $provOrderNos = $provincelist->pluck('prov_order_no')->toArray();
        
             
        $dept=Department::all();
        $today = now();
 
        $data['province'] = $provincelist;
      
        $data['dept'] = $dept;
    
    
       
       return response()->json($data);

      
    }

 
    public function store(Request $request)
    {
        //
        $data = $request->all();

        // Initialize an empty array to store the parameter numbers
        $selectedStats = [];

        // Loop through the data
        foreach ($data as $key => $value) {
            // Check if the value is 1 and the key starts with 'stat'
            if ($value == 1 && strpos($key, 'stat') === 0) {
                // Extract the parameter number from the key (assuming it starts with "stat")
                $paramNumber = intval(substr($key, 4)); // Extracting the number part of the key
                // Add the parameter number to the selectedStats array
                $selectedStats[] = $paramNumber;
            }
        }

        // Convert the array to a comma-separated string
        $selectedStatsString = implode(',', $selectedStats);
        
        DeptValidation::create([
            'hospital_id'=>$request->cr8hospital,
            'dept_valid'=>$selectedStatsString,
            'last_validation_by'=>$request->creatorid,
            
        ]);



        return response()->json(['success'=>true,"messages"=>"Done","data"=>$request]);
    }

 
    public function show(DeptValidation $deptValidation)
    {
        //
    }


    public function edit(DeptValidation $deptValidation)
    {
        //
    }

  
    public function update(Request $request, DeptValidation $deptValidation)
    {
        //
    }

 
    public function destroy(DeptValidation $deptValidation)
    {
        //
    }
}

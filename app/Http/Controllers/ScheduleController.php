<?php

namespace App\Http\Controllers;

use App\Models\schedule;
use App\Models\User;
use App\Models\Employee;
use App\Models\Province;
use App\Models\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $events = array();
    
      $bookings = schedule::orderBy('start_time','asc')->with(['hospital','province', 'department', 'creator', 'createFor', 'validator'])->get();
      $users = User::all();
    
     $data = $this->create();
      
      foreach($bookings as $booking) {
          $color = null;
          if($booking->task == 'Test') {
              $color = '#924ACE';
          }
          if($booking->task == 'Test 1') {
              $color = '#68B01A';
          }

          $provincename=$booking->province->name;
        
         // dd($provincename);
          //$by=$userNamesById[$booking->created_by];
          //$for=$userNamesById[$booking->create_for];
          $by= $users->where('id',$booking->created_by)->first();
          $for= $users->where('id',$booking->create_for)->first();
          
          
          $forName = $for && isset($for->name) ? $for->name : 'noname';
          
          
          //dd($for->name);

          $events[] = [
              'id'   => $booking->id,
              'title' => $booking->task,
              'provinceName'=>$provincename,
              'province'=>$booking->province_id,
              'hospital' => $booking->hospital_id,
              'hospitalName' => $booking->hospital->name,
              'department' => $booking->department_id,
              'departmentName' => $booking->department->name,
              'status' => $booking->status,
              'start' => $booking->start_time,
              'end' => $booking->end_time,
              'created_by' => $booking->created_by,
              'created_by_name' => $by,
              'create_for_name' => $forName,
              'create_for'=> $booking->create_for,
              'color' => $color
          ];
      }

      //var_dump($request->user_id);
     
      if($request->user_id>0 and $request->user_id!=NULL){
        $filteredEvents = array_filter($events, function ($event) use ($request) {
            //dd($event);
           // dd($event['create_for']);
           
            return $event['create_for'] == $request->user_id;
        });
        
        
        $filteredEvents=array_values($filteredEvents);
        return response()->json(['filterschedule' => $filteredEvents, 'data'=>$data->original]);

      }
   

      return response()->json(['schedule'=>$events,'data'=>$data->original]);
 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
        $role=$user->role;
        $area=$user->employee->area;
        
        if($area==="HO"){
        $provincelist=Province::with('area')->get();
        }else {if(strlen($area)<2){
        $provincelist=Province::with('area')->where('wilayah',$area)->get();
        }else{
        $provincelist=Province::with('area')->where('iss_area_code',$area)->get();}}
        $dept=Department::all();
        $today = now();
        $bunit=Unit::all();

        $specialrole = ['admin','direksi'];

        if($area=="HO")
        {
        if(in_array($role, $specialrole)){
            $employees = Employee::all();
            $employees->load("user");
            $piclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            ];
            }); 
        }
        else{
        $usersamerole=User::where('role',$role)->with('employee')->get();
        $piclist = $usersamerole->map(function($users){
            return[
            'user_id' => $users ? $users->id : "No User ID",
            'name' => $users ? $users->employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            
            ];
            });  
        } 
        
        }
        else 
           { 
            $employees = Employee::Where('area',$area)->get();
            $employees->load("user");
            $piclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini"
            ];
            });

           }
        
        
   


        $data['province'] = $provincelist;
        $data['pic']=$piclist;
        $data['dept'] = $dept;
        $data['bunit']=$bunit;
        $data['role']=$role;
        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            //'title' => 'string',
            'province' => 'string',
            'rs' => 'string',
            'department' => 'string',
            'task' => 'string',
            'createFor'=>'string',
            'start_time' => 'date_format:Y-m-d H:i:s',
            'end_time' => 'date_format:Y-m-d H:i:s|after_or_equal:start_time'
        ]);
        
        //dd($request->create_for);

       
        $user=auth()->user();
        $usid=$user->id;

        
        $booking = schedule::create([
           // 'title' => $request->title,
           'province_id' => $request->province,
           'status'=>0,
           'create_for'=>$request->createFor,
            'hospital_id' => $request->rs,
            'department_id' => $request->department,
            'task' => $request->task,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_by'=>$usid,

        ]);
        
        return response()->json(['success' => true,$booking]);
        




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, schedule $schedule)
    {
        $request->validate([
            //'title' => 'string',
            'province_id' => 'string',
            'hospital_id' => 'string',
            'department_id' => 'string',
            'task' => 'string',
            'create_for'=>'string',
            'start_date' => 'date_format:Y-m-d H:i:s',
            'end_date' => 'date_format:Y-m-d H:i:s|after_or_equal:start_time'
        ]);

        $schedule->update([
            'task'=>$request->task,
            'province_id'=>$request->province_id,
            'hospital_id'=>$request->hospital_id,
            'department_id'=>$request->department_id,
            'create_for'=>$request->create_for,
            'start_time'=>$request->start_date,
            'end_time'=>$request->end_date,

        ]);
           
     
        return response()->json(['message' => 'Schedule updated successfully']);


        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(schedule $schedule)
    {
        //
    }
}

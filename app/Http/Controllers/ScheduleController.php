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
      $bookings = schedule::orderBy('start_time','asc')->with(['hospital', 'department', 'creator', 'createFor', 'validator'])->get();
      $users = User::all();
      $userNamesById = $users->pluck('name', 'id')->all();
      $data = $this->create();
      
      foreach($bookings as $booking) {
          $color = null;
          if($booking->task == 'Test') {
              $color = '#924ACE';
          }
          if($booking->task == 'Test 1') {
              $color = '#68B01A';
          }

          $by=$booking->created_by;
          $for=$booking->create_for;

          //dd($userNamesById[$by]);

          $events[] = [
              'id'   => $booking->id,
              'title' => $booking->task,
              'hospital' => $booking->hospital_id,
              'hospitalName' => $booking->hospital->name,
              'department' => $booking->department_id,
              'departmentName' => $booking->department->name,
              'status' => $booking->status,
              'start' => $booking->start_time,
              'end' => $booking->end_time,
              'created_by' => $booking->created_by,
              'created_by_name' => $userNamesById[$by],
              'create_for_name' => $userNamesById[$for],
              'create_for'=> $booking->create_for,
              'color' => $color
          ];
      }

      //var_dump($request->user_id);

      if($request->user_id>0 and $request->user_id!=NULL){
        $filteredEvents = array_filter($events, function ($event) use ($request) {
            return $event['create_for'] == $request->user_id;
        });

        $filteredEvents=array_values($filteredEvents);

        return response()->json(['filterschedule' => $filteredEvents, 'userdata' => $userNamesById,'data'=>$data->original]);

      }
   

      return response()->json(['schedule'=>$events,'userdata'=>$userNamesById,'data'=>$data->original]);
 
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
        if($area=="HO"){
        $provincelist=Province::all();
        }else if(strlen($area)<2){
        $provincelist=Province::with('area')->where('wilayah',$area)->get();
        }else
        $provincelist=Province::with('area')->where('iss_area_code',$area)->get();
        $dept=Department::all();
        $today = now();
        $bunit=Unit::all();

        
        if(strlen($area)<2)
             {$provincelist=Province::with('area')->where('wilayah',$area)->get();}
            else
            {$provincelist=Province::with('area')->where('iss_area_code',$area)->get();}
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
            'title' => 'string',
            'province' => 'string',
            'rs' => 'string',
            'department' => 'string',
            'task' => 'string',
            'start_time' => 'date_format:Y-m-d H:i:s',
            'end_time' => 'date_format:Y-m-d H:i:s|after_or_equal:start_time'
        ]);
        
        $color = null;
        if ($request->title == 'Test') {
            $color = '#924ACE';
        }

        $user=auth()->user();
        $usid=$user->id;

        $booking = schedule::create([
           // 'title' => $request->title,
           // 'province_id' => $request->province,
            'hospital_id' => $request->rs,
            'department_id' => $request->department,
            'task' => $request->task,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_by'=>$usid,

        ]);
        
        return response()->json([
            'id' => $booking->id,
            'start' => $booking->start_date,
            'end' => $booking->end_date,
            'task' => $booking->task,
            'hospital_id' => $booking->hospital_id,
            'department_id' => $booking->department_id,
            'color' => $color ?: '',
        ]);




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
        //
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

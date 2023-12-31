<?php

namespace App\Http\Controllers;

use App\Models\schedule;
use App\Models\User;
use Illuminate\Http\Request;

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
      
      foreach($bookings as $booking) {
          $color = null;
          if($booking->task == 'Test') {
              $color = '#924ACE';
          }
          if($booking->task == 'Test 1') {
              $color = '#68B01A';
          }

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
              'created_by_name' => $userNamesById[$booking->created_by],
              'create_for_name' => $userNamesById[$booking->create_for],
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

        return response()->json(['filterschedule' => $filteredEvents, 'userdata' => $userNamesById]);

      }
   

      return response()->json(['schedule'=>$events,'userdata'=>$userNamesById]);
 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

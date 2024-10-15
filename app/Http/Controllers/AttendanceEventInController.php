<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\attendance_event_in;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;



class AttendanceEventInController extends Controller
{
    //


    public function index()
    {
        return view('EventIn');
    }

    public function kehadiran(){
        $usid=Auth::user()->id;
        $hariini=attendance_event_in::where('user_id',$usid)->whereDate("created_at",today())->doesntHave('out')->first();


        if(isset($hariini)){
            $hariini->created_at = $hariini->created_at->addHours(7);
            $urlphoto =str_replace("https://drive.google.com/uc?id=", "https://drive.google.com/thumbnail?id=", $hariini->photo_data);
            $urlphotoshow =str_replace("&export=media", "",$urlphoto);

            return view('check-out',compact(['hariini','urlphotoshow']));
        }
        return view('check-in');


    }



    public function store(Request $request)
    {
        //
        //dd($request);
        //$attendance = new Attendance();
        //$attendance->place_name = $request->input('place_name');
        //$attendance->address = $request->input('address');
        //$attendance->check_in_at = now();
       // $attendance->save();
       $photoData = $request->input('photo_data');
       $photoFilename= uniqid() . '.png';
       $photoPath = 'photos/' . $photoFilename; // Unique filename, adjust as needed
       $photoPaths=public_path($photoPath);
       //echo $photoPath;
       
       list($type, $data) = explode(';', $photoData);
       list(, $data)      = explode(',', $data);
       $data = base64_decode($data);
       
       //file_put_contents('test.jpg', $data);
       
       Storage::disk('google')->put($photoFilename, $data);
       //file_put_contents($photoPaths,$data);
       $photoUrl = Storage::disk('google')->url($photoFilename);
        //dd($photoUrl);


        $usid=Auth::user()->id;
       // Create a new Attendance instance with the data
       $attendanceEventsIn = attendance_event_in::create ([
            'user_id' =>$usid,
            'event_id'=>109,
           'place_name' => $request->input('place_name'),
           'address' => $request->input('address'),
           'check_in_loc'=>$request->input('check_in_loc'),
           'photo_data' => $photoUrl,
       ]);
   
       dd($attendanceEventsIn);
       // Save the Attendance instance to the database


        return response()->json(['success' => true]);

    }
}

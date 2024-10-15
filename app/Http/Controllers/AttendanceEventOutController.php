<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\attendance_event_out as checkout;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;


class AttendanceEventOutController extends Controller
{

    public function index()
    {
        return view('EventOut');
    }

    public function outstore(Request $request)
    {
        //
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
       $attendance = new checkout([
            'user_id' =>$usid,
            'event_id' =>109,
            'checkin_id'=>$request->input('checkinid'),
           'place_name' => $request->input('place_name'),
           'address' => $request->input('address'),
           'check_out_loc'=>$request->input('check_in_loc'),
           'photo_data' => $photoUrl,
       ]);
   
       // Save the Attendance instance to the database
       $attendance->save();

        return response()->json(['success' => true]);
    }
}

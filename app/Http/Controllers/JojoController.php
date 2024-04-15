<?php

namespace App\Http\Controllers;

use App\Models\jojo;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use App\Models\Prospect;
use App\Models\prospectTemperature;
use App\Models\Review;
use Carbon\Carbon;


class JojoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('check-in');
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

    public function testkirim()
    {
 
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

       // Create a new Attendance instance with the data
       $attendance = new Attendance([
           'place_name' => $request->input('place_name'),
           'address' => $request->input('address'),
           'check_in_loc'=>$request->input('check_in_loc'),
           'photo_data' => $photoUrl,
       ]);
   
       // Save the Attendance instance to the database
       $attendance->save();

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\jojo  $jojo
     * @return \Illuminate\Http\Response
     */
    public function show(jojo $jojo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\jojo  $jojo
     * @return \Illuminate\Http\Response
     */
    public function edit(jojo $jojo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\jojo  $jojo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, jojo $jojo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\jojo  $jojo
     * @return \Illuminate\Http\Response
     */

    public function fixtemperature(){
      // Get all prospects with their associated reviews and temperatures
    $prospects = Prospect::with('review', 'temperature')->get();
    
    // Iterate through each prospect
    foreach ($prospects as $prospect) {
        // Get the review and temperature
        $review = $prospect->review;
        $temperature = $prospect->temperature;

        // Determine the new temperature name and code based on conditions
        if ($review->chance == 0) {
            $tempName = 'DROP';
            $tempCodeName = '0';
        } elseif (in_array($review->anggaran_status, ['Belum Ada', 'Usulan','Belum Tahu']) || $review->chance == 0.2) {
            $tempName = 'EARLY STAGE';
            $tempCodeName = '1';
        } elseif ($review->chance < 0.5 && $review->chance > 0.2) {
            $tempName = 'FUNNEL';
            $tempCodeName = '2';
        } elseif ($review->chance > 0.6 && Carbon::parse($prospect->eta_po_date)->addDays(150)->isPast()) {
            $tempName = 'HOT PROSPECT';
            $tempCodeName = '4';
        } elseif (Carbon::parse($prospect->eta_po_date)->isPast()) {
            $tempName = 'MISSED';
            $tempCodeName = '-1';
        } else {
            $tempName = 'PROSPECT';
            $tempCodeName = '3';
        }

        // Update the temperature in the database
        $temperature->tempName = $tempName;
        $temperature->tempCodeName = $tempCodeName;
        $temperature->save();
    }
    }


    public function destroy(jojo $jojo)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\jojo;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceOut;
use App\Models\attendance_event_in;
use App\Models\attendance_event_out;

use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use App\Models\Prospect;
use App\Models\prospectTemperature;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


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

    public function indx(){
        return view('check-out');
    }

    public function kehadiran(){
        $usid=Auth::user()->id;
        $hariini=Attendance::where('user_id',$usid)->whereDate("created_at",today())->doesntHave('out')->first();


        if(isset($hariini)){
            $hariini->created_at = $hariini->created_at->addHours(7);
            $urlphoto =str_replace("https://drive.google.com/uc?id=", "https://drive.google.com/thumbnail?id=", $hariini->photo_data);
            $urlphotoshow =str_replace("&export=media", "",$urlphoto);

            return view('check-out',compact(['hariini','urlphotoshow']));
        }
        return view('check-in');


    }

    public function kehadiranevent(){
        $usid=Auth::user()->id;
        $hariini=attendance_event_in::where('user_id',$usid)->whereDate("created_at",today())->doesntHave('out')->first();


        if(isset($hariini)){
            $hariini->created_at = $hariini->created_at->addHours(7);
            $urlphoto =str_replace("https://drive.google.com/uc?id=", "https://drive.google.com/thumbnail?id=", $hariini->photo_data);
            $urlphotoshow =str_replace("&export=media", "",$urlphoto);

            return view('EventOut',compact(['hariini','urlphotoshow']));
        }
        return view('EventIn');


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

     public function chancecontrol(Request $request){
        $id = $request->id;
        $temper = prospectTemperature::where('prospect_id',$id)->get();
        $rev= Review::where('prospect_id',$id)->get();
        $etapodate = Carbon::parse($rev->eta_po_date);
        $now= Carbon::now();
        $diff =$etapodate->diffInDays($now,false);

        $n[0]=$rev->first_offer_date?10000000:0;
        $n[6]=$rev->last_offer_date?10:0;
        


        if($rev->anggaran_status=="Belum Ada" || $rev->anggaran_status =="Usulan"){
            $n[1]=0;
        }elseif($rev->anggaran_status=="Ada Saingan" || $rev->anggaran_status=="Ada Neutral"){
            $n[1]=1000000;
        }elseif($rev->anggaran_status=="Ada Sesuai"){
            $n[1]=3000000;
        }

        if($diff>=(-150)){
            $n[2]=100000;
        }


        if($rev->user_status=="Belum Tahu"||$rev->user_status=="Menolak"){
            $n[3]=0;
        }else{$n[3]=10000;}

        if($rev->purchasing_status=="Belum Tahu"||$rev->purchasing_status=="Menolak"){
            $n[4]=0;
        }else{$n[4]=1000;}
        
        if($rev->direksi_status=="Belum Tahu"||$rev->direksi_status=="Menolak"){
            $n[5]=0;
        }else{$n[5]=100;}
        
        if($rev->buy_status=="Done"){
            $n[7]=5;
        }else{$n[7]=0;}

        $sum=array_sum($n);
        

         
            


        //var_dump($diff);
        
        
     }


     public function store(Request $request)
    {
        $validated = $request->validate([
            'place_name' => 'required|string',
            'address' => 'required|string',
            'check_in_loc' => 'required|string',
            'photo_data' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $photoData = $request->input('photo_data');
            $photoFilename = uniqid() . '.png';
            $photoPath = 'photos/' . $photoFilename;

            // Decode and save photo locally
            list($type, $data) = explode(';', $photoData);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $photoPaths=public_path($photoPath);
            //echo $photoPath;
        
            //file_put_contents('test.jpg', $data);
            
            Storage::disk('google')->put($photoFilename, $data);
            //file_put_contents($photoPaths,$data);
            $photoUrl = Storage::disk('google')->url($photoFilename);

            $attendance = new Attendance([
                'user_id' => Auth::user()->id,
                'place_name' => $request->input('place_name'),
                'address' => $request->input('address'),
                'check_in_loc' => $request->input('check_in_loc'),
                'photo_data' => $photoUrl,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);

            $attendance->save();

            return response()->json(['success' => true, 'message' => 'Check-in saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving check-in: ' . $e->getMessage()], 500);
        }
    }

    public function outstore(Request $request)
    {
        $validated = $request->validate([
            'checkinid' => 'required|exists:attendances,id', // Ensure the checkin_id exists in the database
            'place_name' => 'required|string',
            'address' => 'required|string',
            'check_out_loc' => 'required|string',
            'photo_data' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            // Process the photo_data (base64-encoded image)
            $photoData = $request->input('photo_data');
            $photoFilename = uniqid() . '.png';
            $photoPath = 'photos/' . $photoFilename;

            // Decode and save the photo locally
            list($type, $data) = explode(';', $photoData);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
   // Alternatively, save to Google Drive
            $photoUrl = Storage::disk('google')->put($photoFilename, $data);
            $photoUrl = Storage::disk('google')->url($photoFilename);

            // Save Check-Out data
            $attendance = new AttendanceOut([
                'user_id' => Auth::user()->id,
                'checkin_id' => $request->input('checkinid'), // Reference to the Check-In record
                'place_name' => $request->input('place_name'),
                'address' => $request->input('address'),
                'check_out_loc' => $request->input('check_out_loc'),
                'photo_data' => $photoUrl, // URL to the uploaded photo
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);

            $attendance->save();

            return response()->json(['success' => true, 'message' => 'Check-Out saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving Check-Out: ' . $e->getMessage()], 500);
        }
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
        
        $etapodate = Carbon::parse($prospect->eta_po_date);
        $now= Carbon::now();
        $diff =$etapodate->diffInDays($now,false);
        var_dump($diff);
    
        //var_dump($usercek);
        
        
        // Determine the new temperature name and code based on conditions
        if($diff>0){var_dump("PAST");}


        if ($diff>0 and $review->chance != 0) {
            $tempename = 'MISSED';
            $tempecode = '-1';
        } else
        {

            if ($review->chance == 1) {
                $tempename = 'SUCCESS';
                $tempecode = '5';
            } else 
            {
                if ($review->chance == 0) {
                    $tempename = 'DROP';
                    $tempecode = '0';
                } else
                {
                    if ($review->chance >= 0.6 && $review->chance < 1 &&$diff>=(-150) && $diff<=0 && $review->anggaran_status=="Ada Sesuai" && isset($review->user_status) && isset($review->direksi_status) && isset($review->purchasing_status) ){
                        $tempename = 'HOT PROSPECT';
                        $tempecode = '4';
                    } else
                    {
                        
                            if (in_array($review->anggaran_status, ['Belum Ada', 'Usulan','Belum Tahu']) || $review->chance == 0.2) {
                                $tempename = 'LEAD';
                                $tempecode = '1';
                            }
                            else
                            {
                                if (in_array($review->anggaran_status, ['Ada Sesuai', 'Ada Neutral','Ada Saingan'])&& $review->chance > 0.2 && $review->chance <0.7 && isset($review->user_status) && isset($review->direksi_status) && isset($review->purchasing_status) ){
                                    $tempename = 'FUNNEL';
                                    $tempecode = '3';
                                }
                                
                                else{

                                    if ($review->chance >= 0.4 && $review->chance < 0.8 && isset($review->first_offer_date)) {
                                        $tempename = 'Prospect';
                                        $tempecode = '2';
                                    }
                                    else
                                    {
                                        $tempename = 'Prospect';
                                        $tempecode = '2'; 
                                    }                

                                }
                            }

                        }
                    }
                }
            }
        
        $datacekk =$review->chance >= 0.6 && Carbon::parse($prospect->eta_po_date)->addDays(150)->isPast()&&$review->anggaran_status=="Ada Sesuai" &&(isset($review->user_status) || isset($review->direksi_status) || isset($review->purchasing_status)) ;

       


        // Update the temperature in the database
        $temperature->tempName = $tempename;
        $temperature->tempCodeName = $tempecode;
        $temperature->save();
        var_dump($temperature->tempCodeName);
      
    }

    

    

    //return redirect('/home');
    }

public function temperupdate($id,Request $request){
    //dd($request->update);
$update = $request->update; 
    if(isset($update)){
    $prospect = Prospect::with('review', 'temperature')->where('id',$id)->first();


     
        // Get the review and temperature
        $review = $prospect->review;
   

        $temperature = $prospect->temperature;
        
        $etapodate = Carbon::parse($prospect->eta_po_date);
        $now= Carbon::now();
        $diff =$etapodate->diffInDays($now,false);
        var_dump($diff);
    
        //var_dump($usercek);
        
        
        // Determine the new temperature name and code based on conditions
        if($diff>0){var_dump("PAST");}


        

            if ($review->chance == 1) {
                $tempename = 'SUCCESS';
                $tempecode = '5';
            } else 
            {
                if ($review->chance == 0) {
                    $tempename = 'DROP';
                    $tempecode = '0';
                } else
                {
                    if ($review->chance >= 0.6 && $review->chance < 1 &&$diff>=(-150) && $diff<=0 && $review->anggaran_status=="Ada Sesuai" && isset($review->user_status) && isset($review->direksi_status) && isset($review->purchasing_status) ){
                        $tempename = 'HOT PROSPECT';
                        $tempecode = '4';
                    } else
                    {
                        if ($diff>0) {
                            $tempename = 'MISSED';
                            $tempecode = '-1';
                        } else
                        {
                            if (in_array($review->anggaran_status, ['Belum Ada', 'Usulan','Belum Tahu']) || $review->chance == 0.2) {
                                $tempename = 'LEAD';
                                $tempecode = '1';
                            }
                            else
                            {
                                if (in_array($review->anggaran_status, ['Ada Sesuai', 'Ada Neutral','Ada Saingan'])&& $review->chance > 0.4 && $review->chance <0.7 && isset($review->user_status) && isset($review->direksi_status) && isset($review->purchasing_status) ){
                                    $tempename = 'FUNNEL';
                                    $tempecode = '3';
                                }
                                
                                else{

                                    if ($review->chance >= 0.4 && $review->chance < 0.8 && isset($review->first_offer_date)) {
                                        $tempename = 'Prospect';
                                        $tempecode = '2';
                                    }
                                    else
                                    {
                                        $tempename = 'Prospect';
                                        $tempecode = '2'; 
                                    }                

                                }
                            }

                        }
                    }
                }
            }

            $temperature->tempName = $tempename;
            $temperature->tempCodeName = $tempecode;
            $temperature->save();


    return response()->json(["success"=>true,"pesan"=>$temperature->tempName]);
    }
    else
    return response()->json(["success"=>true,"pesan"=>"Ini adalah menu detail prospect, Silahkan Update Review Prospect Anda"]);
}

    public function prospectsequence(){
        
    }

    public function destroy(jojo $jojo)
    {
        //
    }
}

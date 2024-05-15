<?php

namespace App\Http\Controllers;

use App\Models\jojo;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceOut;
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

            $urlphoto =str_replace("https://drive.google.com/uc?id=", "https://drive.google.com/thumbnail?id=", $hariini->photo_data);
            $urlphotoshow =str_replace("&export=media", "",$urlphoto);

            return view('check-out',compact(['hariini','urlphotoshow']));
        }
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
       $attendance = new Attendance([
            'user_id' =>$usid,
           'place_name' => $request->input('place_name'),
           'address' => $request->input('address'),
           'check_in_loc'=>$request->input('check_in_loc'),
           'photo_data' => $photoUrl,
       ]);
   
       // Save the Attendance instance to the database
       $attendance->save();

        return response()->json(['success' => true]);

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
       $attendance = new AttendanceOut([
            'user_id' =>$usid,
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

public function temperupdate(Request $request){

}

    public function prospectsequence(){
        
    }

    public function destroy(jojo $jojo)
    {
        //
    }
}

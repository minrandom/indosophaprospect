<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Employee;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    
    




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
        
  public static function generateAlerts($prospects,$alertType)
{
        
        $user = auth()->user();
    $roles= $user->role;
   


    function getareaman($isi,$isi2)
    {
        $am=Employee::with('user')->where([['area',$isi],['position','AM']])->get();
        $nsm=Employee::with('user')->where([['area',$isi2],['position','NSM']])->get();
        $result = [];

        if ($am->count() > 0) {
            $useram = $am->pluck('user.name');
            $useramid = $am->pluck('user.id');
            $arr1=json_decode($useramid);
            $array = json_decode($useram);
            $result['amid']=$arr1[0];
            $result['am'] = $array[0];
        } else {
            $result['am'] = "0";
        }
    
        if ($nsm->count() > 0) {
            $usernsm = $nsm->pluck('user.name');
            $usernsmid = $nsm->pluck('user.id');
            $arr1=json_decode($usernsmid);
            $array = json_decode($usernsm);
            $result['nsmid']=$arr1[0];
            $result['nsm'] = $array[0];
        } else {
            $result['nsm'] = "0";
        }
    
        return $result;
    };

    
    foreach ($prospects as $prospect) {
     
        $userdata = getareaman($prospect->province->iss_area_code, $prospect->province->wilayah);
        $existingAlert = Alert::where('prospect_id', $prospect->id)->where('type', $alertType)->first();

       
        // Create alert if an appropriate alert type is generated
        if (!$existingAlert) {
            if ($userdata['am'] != "0") {
                Alert::create([
                    'type' => $alertType,
                    'prospect_id' => $prospect->id,
                    'user_id' => $userdata['amid']
                ]);
            }
            if ($userdata['nsm'] != "0") {
                Alert::create([
                    'type' => $alertType,
                    'prospect_id' => $prospect->id,
                    'user_id' => $userdata['nsmid']
                ]);
            }
        }
    }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show(Alert $alert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function edit(Alert $alert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alert $alert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alert $alert)
    {
        //
    }
}

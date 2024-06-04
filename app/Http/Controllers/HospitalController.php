<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Province;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.hospital');
    }


    public function getHospitalsByProvince($provinceId)
    {
    // Retrieve hospitals based on the province ID
    //dd($provinceId);
    $hospitals = Hospital::where('province_id', $provinceId)->get();
       
    return response()->json(['hosopt' => $hospitals]);
    }

    public function getHospitalsByProvince2($provinceId)
    {
    // Retrieve hospitals based on the province ID
    //dd($provinceId);
    $hospitals = Hospital::with('validDept')->where('province_id', $provinceId)->get();
        $rs=$hospitals->filter(function($hos) {
            return $hos->validDept === null;
        })->map(function($hos) {
            return ['id' => $hos->id, 'name' => $hos->name];
        })->values();
        //dd($rs);
    return response()->json(['hosopt' => $rs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function createhosdata()
    {
        $provinces = Province::all();
        return response()->json(['prov'=>$provinces]);

    }

    public function create()
    {
        //

   

        return view('admin.hospitalcreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $province = Province::where('prov_region_code',$request->province)->first();
        $hospital = Hospital::orderBy('id','desc')->first();
        $inputString = "RS03405";
        // Extract the non-numeric part (e.g., "RS")
        $prefix = preg_replace("/[0-9]/", "", $inputString);
        // Extract the numeric part (e.g., "03405")
        $number = preg_replace("/[^0-9]/", "", $inputString);
        // Increment the numeric part by one
        $newNumber = intval($number) + 1;
        // Pad the new number with leading zeros to match the length of the original number
        $newNumberPadded = str_pad($newNumber, strlen($number), '0', STR_PAD_LEFT);
        // Concatenate the non-numeric part with the new padded number
        $newcode = $prefix . $newNumberPadded;
        //dd($reu)
        Hospital::create([
            "code"=>$newcode,
            "name"=>$request->name,
            "prov_id"=>$province->id,
            "city"=>$request->cityname,
            "city_order_no"=>$request->city,
            "category"=>$request->category,
            "address"=>$request->Alamat,
            "ownership"=>$request->swasta,
            "owned_by"=>$request->tipe,
            "class"=>$request->class,
            "akreditas"=>$request->akreditas,
            "target"=>$request->target,

        ]);
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function show(Hospital $hospital)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function edit(Hospital $hospital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hospital $hospital)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hospital $hospital)
    {
        //
    }
}

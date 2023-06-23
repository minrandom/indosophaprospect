<?php

namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.prospect');
    }

    public function validationprospect()
    {
        return view('admin.prospectvalidation');
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function show(Prospect $prospect)
    {
        //\
        return view('admin.prospectdetail');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function edit(Prospect $prospect)
    {
        //
        $prospect->load("creator","hospital","review","province","department","unit","config");
        $provOpt= Province::all();
        //$peric=$prospect->personInCharge->name;
        return view('admin.prospectedit',compact(
            'prospect','provOpt'
        ));
    }
    public function validation(Prospect $prospect)
    {
        //
        $prospect->load("creator","hospital","review","province","department","unit","config");
     
        return response()->json($prospect);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prospect $prospect)
    {
        //
    }
    public function validationupdate(Request $request, Prospect $prospect)
    {
        //
        $prospect->update([
            'status' => $request->input('validation')
        ]);
        //return response()->json(['message' => 'Validation status berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prospect  $prospect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prospect $prospect)
    {
        //
    }
}

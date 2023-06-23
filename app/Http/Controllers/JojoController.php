<?php

namespace App\Http\Controllers;

use App\Models\jojo;
use Illuminate\Http\Request;
use App\Models\Attendance;

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

        Attendance::create($request->all());

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
    public function destroy(jojo $jojo)
    {
        //
    }
}

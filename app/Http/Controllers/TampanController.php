<?php

namespace App\Http\Controllers;

use App\Models\tampan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//use Illuminate\Support\ServiceProvider;

class TampanController extends Controller
{

    private function token(){
        $client_id=\Config('services.google.clientId');
        //dd($client_id);
        $client_secret=\Config('services.google.clientSecret');
        $refresh_token=\Config('services.google.refreshToken');
        $isian='refresh_token';
        //dd($refresh_token);

        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
        ]);
        //dd($response[]);
        $accessToken=json_decode((string)$response->getBody(),true);
        //dd($accessToken);
        return $accessToken;
    }

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
        $accessToken= $this->token();
        dd($accessToken);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tampan  $tampan
     * @return \Illuminate\Http\Response
     */
    public function show(tampan $tampan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tampan  $tampan
     * @return \Illuminate\Http\Response
     */
    public function edit(tampan $tampan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tampan  $tampan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tampan $tampan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tampan  $tampan
     * @return \Illuminate\Http\Response
     */
    public function destroy(tampan $tampan)
    {
        //
    }
}

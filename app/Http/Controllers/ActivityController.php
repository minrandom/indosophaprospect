<?php

namespace App\Http\Controllers;
use App\Models\Activity;
use App\Models\Hospital;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {


        $activities = Activity::where('user_id', Auth::id())->get();
        $provinces = Province::all();
        return view('admin.scheduling', compact('activities','provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'purpose' => 'required|string',
            'hospital_id' => 'required|exists:hospitals,id',
            'departement_id' => 'required|exists:departments,id',
        ]);

        $activity = Activity::create([
            'user_id' => Auth::id(),
            'hospital_id' => $request->hospital_id,
            'departement_id' => $request->departement_id,
            'purpose' => $request->purpose,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($activity);
    }

    public function getHospitalsByProvince($provinceId)
{
    $hospitals = Hospital::where('province_id', $provinceId)->get();
    return response()->json($hospitals);
}
}

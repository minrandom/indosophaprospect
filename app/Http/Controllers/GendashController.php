<?php

namespace App\Http\Controllers;
use App\Models\Hospital;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\Department;
use App\Models\installbase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GendashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $today = Carbon::today()->toDateString();
        $countinstallbase = installbase::orderby('id')->count();
        $hospitalWithIB = installbase::distinct()
        ->count('hospital_id');
        $prospect = Prospect::query()
            ->whereDate('eta_po_date', '>', $today)
            ->whereHas('review', function ($q) {
                $q->where('chance', '>', 0)->where('chance', '<', 1);
            });

         $countprospect = (clone $prospect)->count();
        $totalValue = (clone $prospect)->sum('submitted_price');

        $prospectData=[
            'count'=>$countprospect,
            'value'=>$totalValue
        ];




        $quickMenus = [
        ['label' => 'Add IB', 'route' => 'addroute here'/*route('')*/],                 // change route name
        ['label' => 'Add Coverage', 'route' => 'addroute here'/*route('')*/],    // change route name
        ['label' => "Add Department\nInformation", 'route' => 'addroute here'/*route('')*/],
        ['label' => 'Task Form', 'route' => 'addroute here'/*route('')*/],
        ['label' => 'Add Task', 'route' => 'addroute here'/*route('')*/],
        ['label' => "Completed\nTask", 'route' => 'addroute here'/*route('')*/],
        ];
        $provinces = Province::orderBy('id')->get(['id','name']);
        return view('admin.gendash',compact('provinces','quickMenus','countinstallbase','hospitalWithIB','prospectData'));
    }


     public function hospitalsByProvince(Province $province)
    {



        $hospitals = Hospital::where('province_id', $province->id)
            ->orderBy('name')
            ->get(['id','name','code','city']);

        return response()->json($hospitals);
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


    public function hospitalDashboard(Hospital $hospital)
    {

       $today = Carbon::today()->toDateString();
        $departments = Department::orderBy('alt_name','asc')->get();
         $installbase = installbase::query()
            ->where('hospital_id', $hospital->id);
        // Base filter: only valid prospects for this hospital
        $base = Prospect::query()
            ->where('hospital_id', $hospital->id)
            ->whereDate('eta_po_date', '>', $today)
            ->whereHas('review', function ($q) {
                $q->where('chance', '>', 0)->where('chance', '<', 1);
            });

        $sixMonthsLater = Carbon::today()->addMonths(6);






        // KPI: total prospects
        $totalProspects = (clone $base)->count();
        $totalib=(clone $installbase)->count();

        $underWarranty= (clone $installbase)
        ->whereNotNull('end_of_warranty')
        ->whereDate('end_of_warranty', '>=', $today)
        ->count();

        $notunderWarranty= (clone $installbase)
        ->where(function ($q) use ($today) {
            $q->whereNull('end_of_warranty')
            ->orWhereDate('end_of_warranty', '<', $today);
            })->count();

        $warrantyStats = (clone $installbase)
        ->selectRaw("
            SUM(CASE
                WHEN end_of_warranty > ? THEN 1
                ELSE 0
            END) as active,

            SUM(CASE
                WHEN end_of_warranty BETWEEN ? AND ? THEN 1
                ELSE 0
            END) as ending_soon,

            SUM(CASE
                WHEN end_of_warranty < ? OR end_of_warranty IS NULL THEN 1
                ELSE 0
            END) as finished
        ", [
            $sixMonthsLater,
            $today,
            $sixMonthsLater,
            $today
        ])
        ->first();


        // KPI: total value (choose 1)
        // Option A: sum submitted_price only
        $totalValue = (clone $base)->sum('submitted_price');

        $tempRows = (clone $base)
            ->join('prospect_temperatures as pt', 'pt.prospect_id', '=', 'prospects.id')
            ->select('pt.tempName', DB::raw('COUNT(*) as total'))
            ->groupBy('pt.tempName')
            ->orderByDesc('total')
            ->get();

        $tempLabels = $tempRows->pluck('tempName')->toArray();
        $tempData   = $tempRows->pluck('total')->toArray();

        // Bar: by unit
        $unitRows = (clone $base)
            ->join('units as u', 'u.id', '=', 'prospects.unit_id')   // ✅ assumes you have units table
            ->select('u.name as unit_name', DB::raw('COUNT(*) as total'))
            ->groupBy('u.name')
            ->orderByDesc('total')
            ->get();

        $unitLabels = $unitRows->pluck('unit_name')->toArray();
        $unitData   = $unitRows->pluck('total')->toArray();

        // Pass to blade
        $prospectWidgets = [
            'total' => $totalProspects,
            'value' => (int) $totalValue,
            'temp' => [
                'labels' => $tempLabels,
                'data' => $tempData,
            ],
            'unit' => [
                'labels' => $unitLabels,
                'data' => $unitData,
            ]
        ];




        // Dummy data for top section
        $top = [
            'city' => $hospital->city,
            'specialities' => ['Cardiovascular', 'Urologi', 'ICU'],
            'visits' => 35,
            'total_equipment' => $totalib,
            'under_contract' => [$underWarranty, $notunderWarranty], // %
            'warranties' => [$warrantyStats->active, $warrantyStats->ending_soon, $warrantyStats->finished],
            'departments' => [
                'labels' => ['ICU', 'Urologi', 'Cardiovascular'],
                'data' => [8, 12, 33],
            ],
            'key_people' => [
                ['name' => '...', 'role' => 'Doctor', 'department' => 'Urologi', 'phone' => '+62 812049', 'influence' => 'Financial'],
                ['name' => '...', 'role' => 'Nurse', 'department' => 'Urologi', 'phone' => '+62 812049', 'influence' => 'Financial'],
                ['name' => '...', 'role' => 'Director', 'department' => 'Urologi', 'phone' => '+62 812049', 'influence' => 'Financial'],
            ]
        ];

        return view('admin.hospitaldashboard', compact('hospital','top','prospectWidgets','departments'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

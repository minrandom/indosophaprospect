<?php

namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\Department;
use App\Models\Hospital;
use App\Models\Prospect;
use App\Models\Config;
use App\Models\User;
use App\Models\Employee;
use App\Models\rejectLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
    public function create(Prospect $prospect)
    {
        //
        $provincelist=Province::all();
        $rumahsakit=Hospital::all();
        $dept=Department::all();
        $produk=Config::all();
        $data['province'] = $provincelist;
        $data['rumahsakit'] = $rumahsakit;
        $data['dept'] = $dept;
        $data['produk'] = $produk;
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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

   function optiondata()
            {
                $dataoption=[];
                $dataoption['source'] = array(
                   [ ['id'=>0,
                    "name" => "Request By Customer",],
                   [ 'id'=>1,
                    "name" => "Visit",
                     ],
                   [ 'id'=>2,
                    "name" =>  "Promotion Plan By BU",
                     ],
                   [ 'id'=>3,
                    "name" =>   "Promotion Plan By Sales Team",
                     ],
                   [ 'id'=>4,
                    "name" =>     "Event",
                     ],   
                    ]
                    );
                $dataoption['anggaran']=array(
                    'review'=>[
                        ['id'=>0,'name'=>'Belum Ada'],
                        ['id'=>1,'name'=>'Usulan'],
                        ['id'=>2,'name'=>'Ada Sesuai'],
                        ['id'=>3,'name'=>'Ada Neutral'],
                        ['id'=>4,'name'=>'Ada Saingan']
                        ],
                    'Jenis'=>[
                        ['id'=>0,'name'=>'DAK'],
                        ['id'=>1,'name'=>'BLU / BLUD'],
                        ['id'=>2,'name'=>'APBN'],
                        ['id'=>3,'name'=>'APBD'],
                        ['id'=>4,'name'=>'Ba BUN'],
                        ['id'=>5,'name'=>'OTSUS'],
                        ['id'=>6,'name'=>'Ban Prov'],
                        ['id'=>7,'name'=>'Ban Gub'],
                        ['id'=>8,'name'=>'Swasta'],
                        ['id'=>9,'name'=>'Belum Tahu'],
                    ],



                 



                );


                    return response()->json($dataoption);
            }


    public function edit(Prospect $prospect)
    {
        //
    
        $prospect->load("creator","hospital","review","province","department","unit","config");
        $provOpt= Province::all();
        $hospitals = Hospital::where('province_id', $prospect->province_id)->get();
        $sourceoption = $this->optiondata()->getData();
        

        $depopt=Department::all();
        $configlist=Config::all();
        //$peric=$prospect->personInCharge->name;
        return response()->json([
           
        'prospect'=>$prospect,
            'provopt' => $provOpt,
            'hosopt' => $hospitals,
            'depopt'=>$depopt,
            'configlist'=>$configlist,
            'sourceoption'=>$sourceoption         
        ]);
    }





    public function validation(Prospect $prospect)
    {
        //
       $wil=substr($prospect->province->iss_area_code,0,1);
        $prospect->load("creator","hospital","review","province","department","unit","config");
        $employees = Employee::where('area', $prospect->province->iss_area_code)->orWhere('area',$wil)->get();
        $employees->load("user");
        $piclist = $employees->map(function($employee){
        return[
        'user_id' => $employee ? $employee->user->id : "No User ID",
        'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini"
        ];
    });

    $piclist->push(['user_id'=>14,'name'=>'Ayane']);
        $prospect->piclist=$piclist;
     
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
        
        $hospital=Hospital::where('name',$request->hospitalname)->first();
        $config=Config::where('name',$request->productname)->first();
        $hosid=$hospital->id;
        $configid=$config->id;
        $price=$config->price_include_ppn;
        $bunit=$config->unit_id;
        $prospect->update([
           'province_id'=>$request->provinceedit,
           'hospital_id'=>$hosid,
           'config_id'=>$configid,
           'unit_id'=>$bunit,
           'submitted_price'=>$price,
           'qty'=>$request->qtyitem,
           'department_id'=>$request->departmentname
        ]);
    }

    public function validationupdate(Request $request, Prospect $prospect)
    {
        //
        $cek=$request->input('validation');
        switch($cek){
            case 404:
                rejectLog::create([
                    "prospect_id"=>$request->id,
                    
                    "rejected_by"=>$request->validator,
                    "reason"=>$request->reason
                ]);
            break;

            case 1:
                
                $date = Carbon::createFromFormat('Y-m-d', $request->submitdate);

                // Extract the year, month, and day
                $year = $date->format('y');
                $month = $date->format('m');
                $day = $date->format('d');

                // Concatenate the year, month, and day parts
                $codedate = $year . $month . $day;
                $rand3=rand(100,999);



                $prospect_no="ISSP-";
                $prospect_no.=$request->provcode;
                $prospect_no.="-".$codedate."-".$rand3;
                
                $prospect->update([
                    'validation_time'=>Carbon::now(),
                    'validation_by'=>$request->validator,
                    'prospect_no'=>$prospect_no,
                    'pic_user_id'=>$request->personincharge
                ]);
            break;

        }
        $prospect->update([
            'status' => $request->input('validation')
        ]);
        //return $request;
        return response()->json(['message' => 'Berhasil Validasi Prospect,</br> dengan Nomor Prospect : <b>'.$prospect_no.'</b></br> Silahkan Melanjutkan review di Menu Prospect Data']);
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

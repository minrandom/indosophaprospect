<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\Department;
use App\Models\sequence;
use App\Models\Hospital;
use App\Models\Prospect;
use App\Models\Review;
use App\Models\ReviewLog;
use App\Models\Alert as AlertData;
use App\Http\Controllers\AlertController as Alert;
use App\Http\Controllers\sequenceController as seq;

use App\Models\Category;
use App\Models\Config;
use App\Models\Unit;
use App\Models\Event;
use App\Models\tmpProspectInput;
use App\Models\prospectTemperature;
use App\Models\prospectFilters;
use App\Models\User;
use App\Models\Brand;
use App\Models\Employee;
use App\Models\rejectLog;
use App\Models\updatelog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public function creation()
    {
        $username = Auth::check() ? Auth::user()->name : 'Guest';

    return view('admin.prospectcreate', ['username' => $username]);
     
    }
    public function eventcreation()
    {
        $username = Auth::check() ? Auth::user()->name : 'Guest';

    return view('admin.prospectcreate_event', ['username' => $username]);
     
    }
    public function creationcheck()
    {
        return view('admin.prospectcreatecheck');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eventcreate(Prospect $prospect){
        $provincelist = Province::all();
        $employees = Employee::all();
        $employees->load("user");
        $sourceoption = $this->optiondata()->getData();
        
        $piclist = $employees->map(function($employee){
        return[
        'user_id' => $employee ? $employee->user->id : "No User ID",
        'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
        'area' => $employee?$employee->area:"No data "
        ];});
        
        $dept=Department::where('id','>=',"2")->orderBy('alt_name',"asc")->get();
        $today = now();
       $event=Event::where('awal_input',"<=",$today)->where('akhir_input','>=',$today)->get();
        
        $data['province'] = $provincelist;
        //$data['draft']=$draft;
        $data['pic']=$piclist;
       
       // $data['rumahsakit'] = $rumahsakit;
        $data['dept'] = $dept;
        $data['event'] = $event;
        // $data['produk'] = $produk;
        // $data['source']=$sourceoption;
        $bunit=Unit::all();
        $data['bunit'] = $bunit;
        $data['source']=$sourceoption;
        return response()->json($data);
    }

    public function create(Prospect $prospect)
    {
        //
       
        $sourceoption = $this->optiondata()->getData();
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
        $role=$user->role;
        $filter = prospectFilters::where('filterUser',$usid)->first();
        $a = trim($filter->filterData, "[]");
        $arrayfilter = explode(',', $a);
    
        // Replace occurrences of ""0"" with null
    
    
        // Debug the processed array
   // Remove square brackets
  

        $area=$user->employee->area;
        $pos=$user->employee->position;
        if($area=="HO" ){
        $provincelist=Province::orderBy(DB::raw('CAST(prov_order_no AS UNSIGNED)'))->get();
        }else if($role=="nsm"){
        $provincelist=Province::with('area')->where('wilayah',$area)->orderBy(DB::raw('CAST(prov_order_no AS UNSIGNED)'))->get();
         }else if($role==="fs"){
         $provincelist=Province::with('area')->where('prov_order_no',$area)->orWhere('iss_area_code',$area)->orderBy(DB::raw('CAST(prov_order_no AS UNSIGNED)'))->get();
        }else if($role==="am"){
        $areaArray = explode(',', $area);
        $provincelist=Province::with('area')->whereIn('iss_area_code', $areaArray)->orderBy(DB::raw('CAST(prov_order_no AS UNSIGNED)'))->get();}       
     
    
        $provOrderNos = $provincelist->pluck('prov_order_no')->toArray();
       //dd($provOrderNos);
        
        //$rumahsakit=Hospital::all();
        $dept=Department::where('id','>=',"2")->orderBy('alt_name',"asc")->get();
        $today = now();
       $event=Event::where('awal_input',"<=",$today)->where('akhir_input','>=',$today)->get();
       // $produk=Config::all();
        $bunit=Unit::all();
        $draft= tmpProspectInput::where("user_id",$usid)->first();
        
        if($draft){
        if($draft->hospital_id){$hodraft=Hospital::where("id",$draft->hospital_id)->first(); $data['hodraft']=$hodraft;}
        if($draft->hospital_id){ $catdraft=Category::where("id",$draft->category_id)->first(); $data['catdraft']=$catdraft;}
        if($draft->hospital_id){$configdraft=Config::where("id",$draft->config_id)->first(); $data['configdraft']=$configdraft;}
       
        }

        $specialrole = ['admin','direksi','db'];

        if($area=="HO")
        {
        if(in_array($role, $specialrole)){
            $employees = Employee::all();
            $employees->load("user");
            $piclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];
            }); 
        }
        else{
            $employees = Employee::where('position',$pos)->get();
            $piclist = $employees->map(function($employee){
                return[
                'user_id' => $employee ? $employee->user->id : "No User ID",
                'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
                'area' => $employee?$employee->area:"No data "
                ];
                }); 
        }
        }
        else
        {
          if($role=="am"){
                       
             //dd($provdata)
            $employees = Employee::Where('area',$area)->orWhereIn('area',$provOrderNos)->get();
            $employees->load("user");
            //for AM
            $amdata = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];});
            //call F
            $piclist = array_merge($amdata->toArray());


          }else{
            $employees = Employee::where('area', 'LIKE', '%' . $area . '%')->orWhereIn('area',$provOrderNos)->get();
            $employees->load("user");
            $piclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];
            });
          } 
         }
        
        

         if($role=="bu"){
            $employees = Employee::all();
            $employees->load("user");
            $filterpiclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];
            }); 
         }else {$filterpiclist = $piclist;}
         $data['filterpiclist']=$filterpiclist;

        $data['province'] = $provincelist;
        $data['draft']=$draft;
        $data['pic']=$piclist;
       $data['filter']=$arrayfilter;
       // $data['rumahsakit'] = $rumahsakit;
        $data['dept'] = $dept;
        $data['event'] = $event;
       // $data['produk'] = $produk;
        $data['source']=$sourceoption;
        $data['bunit']=$bunit;
        return response()->json($data);
    }

    public function savedraft(Request $request){
   
            $data=tmpProspectInput::where("user_id",$request->creatorid)->first();
            if(empty($data)){
                $n="oke";
            }else
            {
                $data->delete();
            }
            $jenisagg=intval($request->jenisanggarancr8);
           // var_dump($jenisagg);

            tmpProspectInput::create([
                'user_id'=>$request->creatorid,
                'source'=>$request->cr8source,
                'province_id'=>$request->cr8province,
                'hospital_id'=>$request->cr8hospital,
                'department_id'=>$request->cr8department,
                'unit_id'=>$request->cr8bunit,
                'category_id'=>$request->cr8category,
                'config_id'=>$request->cr8product,
                'qty'=>$request->qtyinput,
                'review_anggaran'=>$request->anggarancr8,
                'jns_aggr'=>$jenisagg,
                'eta_po_date'=>$request->etapodatecr8,
            ]);
            
            return response()->json(['success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $data="done";
       $role = Auth::user()->role;
        //$hosid=Hospital::where('name',$request->cr8hospital)->first();
        $config=Config::with('unit','brand')->where('id',$request->cr8product)->first();
        $ss = Event::where('id',$request->cr8source)->first();
        $options = $this->optiondata()->getData();
       //dd($options);
        foreach ($options->source as $option) {
            if ($option->id == $request->cr8source) {
                $sourceoption=$option->name;
            }
            else{
            if($ss){
            $sourceoption = $ss->name;}}
            
        }

        foreach ($options->anggaran->review as $anggaransts) {
            if ($anggaransts->id == $request->anggarancr8) {
                $anggaranopt=$anggaransts->name;
            }
        }
       
        foreach ($options->anggaran->Jenis as $anggaranjns) {
            if(isset($request->jenisanggarancr8)){
            if ($anggaranjns->id == $request->jenisanggarancr8) {
                $anggaranjnsopt=$anggaranjns->name;
            }if(10 == $request->jenisanggarancr8){
                $anggaranjnsopt="MABES AD / AL / AU";
            }
            }else $anggaranjnsopt="Belum Tahu";
        }
        
        $request->validate([
            'qtyinput' => 'required|numeric|min:1',
            // Add other validation rules for your other form fields if needed
        ]);

        $draft=tmpProspectInput::where("user_id",$request->creatorid)->first();
            if(empty($draft)){
                $z="oke";
            }else
            {
                $draft->delete();
            }

            //dd($role);

        if($sourceoption==="Event"){
        $sourceoption.="".$request->eventname;}
            $project=0;
            if($role =='prj'){
                $project = 1;
            }



        $n=Prospect::create([
            'user_creator'=>$request->creatorid,
            'prospect_source'=>$sourceoption,
            'province_id'=>$request->cr8province,
            'hospital_id'=>$request->cr8hospital,
            'department_id'=>$request->cr8department,
            'config_id'=>$request->cr8product,
            'unit_id'=>$request->cr8bunit,
            'is_project'=>$project,
            'submitted_price'=>$config->price_include_ppn,
            'qty'=>$request->qtyinput,
             'eta_po_date'=>$request->etapodatecr8 
        ]);

        $idbaru=$data." idnya ".$n->id;
        $id=$n->id;

       Review::create([
        'prospect_id'=>$id,
        'anggaran_status'=>$anggaranopt,
        'jenis_anggaran'=>$anggaranjnsopt,
        'comment'=>$request->cr8infoextra,
        ]);


        $tempeName="LEAD";
        $tempeCode=1;
        prospectTemperature::create([
            'prospect_id'=>$id,
            'tempName'=>$tempeName,
            'tempCodeName'=>$tempeCode
        ]);
                                      




        $newProspect = Prospect::with("creator","province")
            ->where("id",$id)
            ->get();
        Alert::generateAlerts($newProspect,"V");
      

        return response()->json($id);
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
        $userId = auth()->user()->id;          
           // Check if the sequence data already exists for the user
        $Sequence = Sequence::where('sequenceUser', $userId)->first();

        if (!$Sequence) {
            // If $Sequence is empty, reload the page
            return redirect()->back();
        }


        $a = trim($Sequence->sequenceData, "[]"); // Remove square brackets
        $arraySeq = explode(',', $a); 

        // Get the index of the current prospect ID in $arraySeq
         $prospect->load("personInCharge","creator","hospital","review","province","department","unit","config");
        //$picdata=User::with('employee')->where('id',$prospect->pic_user_id);
        //$brand=Brand::where('id',$prospect->config->brand_id);
        
        $currentIndex = array_search($prospect->id, $arraySeq);

        // Get the IDs of the previous and next prospects
        $prev = ($currentIndex > 0) ? $arraySeq[$currentIndex - 1] : null;
        $next = ($currentIndex < count($arraySeq) - 1) ? $arraySeq[$currentIndex + 1] : null;
        
        $nextprospect= Prospect::where('id',$next)->first();
        $prevprospect= Prospect::where('id',$prev)->first();
        
        
        $ch=$prospect->review->chance;
        $anggaran=$prospect->review->anggaran_status;
        $podate=$prospect->eta_po_date;
        $reviewdata=ReviewLog::with('UpdatedBy')->where('review_id',$prospect->review->id)->orderBy('updated_at','desc')->orderBy('id','desc')->first();
        $colUpdate = $reviewdata->col_update ?? "<span class='btn-danger'>Belum Pernah di Update</span>";

        $temper = prospectTemperature::where('prospect_id',$prospect->id)->first();
        //dd($temper);
        $tempName=$temper->tempName;
        $tempCode=$temper->tempCodeName;
  
        //dd($reviewdata);

        switch ($tempCode){
            case (-1);
            $tempe= "<h4><span class='badge tmpe bg-secondary text-light'>MISSED</span></h4>";
            break;
            case 0:
               $tempe= "<h4><span class='badge tmpe bg-dark text-light'>DROP</span></h4>";
                break;
            case 1:
                $tempe="<h4><span class='badge tmpe text-light' style='background-color:CornflowerBlue'>LEAD</span></h4>";
                break;
            case 2:
                $tempe="<h4><span class='badge tmpe text-light' style='background-color:MediumOrchid'>PROSPECT</span></h4>";
                break;
            case 3:
                $tempe="<h4><span class='badge tmpe text-light' style='background-color:Salmon'>FUNNEL</span></h4>";
                break;
            case 4:
                $tempe="<h4><span class='badge tmpe bg-danger text-light'>HOT PROSPECT</span></h4>";
                break;
            case 5:
                $tempe="<h4><span class='badge tmpe bg-success text-light'>SUCCESS</span></h4>";
                break;
        }




        $provOpt= Province::all();
        return view('admin.prospectedit',compact('prospect','provOpt','tempe','reviewdata','colUpdate','prev','next','prevprospect','nextprospect'));
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
                   ['id'=>0,
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
                 
                
                    );
                    
                $dataoption['naction'] = array(
                   ['id'=>0,
                    "name" => "Mapping/Profiling",],
                   [ 'id'=>1,
                    "name" => "Make First Offer",
                     ],
                   [ 'id'=>2,
                    "name" =>  "Renew Offer",
                     ],
                   [ 'id'=>3,
                    "name" =>   "Courtesy call to Director",
                     ],
                   [ 'id'=>4,
                    "name" =>     "Set up Join Visit",
                     ],   
                   [ 'id'=>5,
                    "name" =>     "Confirm Budget",
                     ],   
                   [ 'id'=>6,
                    "name" =>     "Organize Demo",
                     ],   
                   [ 'id'=>7,
                    "name" =>     "Organize Presentation",
                     ],   
                   [ 'id'=>8,
                    "name" =>     "Provide reference",
                     ],   
                   [ 'id'=>9,
                    "name" =>     "General Follow Up",
                     ],   
                   [ 'id'=>10,
                    "name" =>     "Nego",
                     ],   
                 
                
                    );

                $dataoption['state'] = array(
                   ['id'=>0,
                    "name" => "BELUM TAHU",],
                   [ 'id'=>1,
                    "name" => "NEUTRAL",
                     ],
                   [ 'id'=>2,
                    "name" =>  "POSITIF",
                     ],
                   [ 'id'=>3,
                    "name" =>   "MENOLAK",
                     ],
                   [ 'id'=>4,
                    "name" =>     "SETUJU",
                     ],   
                
                    );
                $dataoption['chance'] = array(
                   [ 'id'=>1,
                    "name" => "20%","data"=>0.2
                     ],
                   [ 'id'=>2,
                    "name" =>  "40%","data"=>0.4
                     ],
                   [ 'id'=>3,
                    "name" =>   "60%","data"=>0.6
                     ],
                   [ 'id'=>4,
                    "name" =>     "80%","data"=>0.8
                     ],   
                  
                
                    );
                $dataoption['validation'] = array(
                   ['id'=>1,
                    "name" => "VALID",],
                   [ 'id'=>99,
                    "name" => "EXPIRED",
                     ],
                   [ 'id'=>404,
                    "name" =>  "REJECT",
                     ],
                   [ 'id'=>0,
                    "name" =>   "NEW",
                     ],
                   
                    
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
                        //['id'=>0,'name'=>'DAK'],
                        ['id'=>1,'name'=>'BLU / BLUD'],
                        ['id'=>2,'name'=>'APBN / Ba BUN / DAK'],
                        ['id'=>3,'name'=>'APBD / OTSUS'],
                        //['id'=>4,'name'=>'Ba BUN'],
                        ['id'=>6,'name'=>'Ban Prov / Ban Gub'],
                        ['id'=>7,'name'=>'Cukai'],
                        ['id'=>8,'name'=>'Swasta'],
                        ['id'=>9,'name'=>'Belum Tahu'],
                    ],
               );


                    return response()->json($dataoption);
            }


    public function edit(Prospect $prospect)
    {
        //
        $role = Auth::user()->role;
        $prospect->load("creator","hospital","review","province","department","unit","config");
        $provOpt= Province::all();
        $bunit=Unit::all();
        $cat=Category::all();
        $hospitals = Hospital::where('province_id', $prospect->province_id)->get();
        $sourceoption = $this->optiondata()->getData();
        $lateinfo=$prospect->review->comment?$prospect->review->comment:"Silahkan Update info";
        $validationTime = Carbon::parse($prospect->validation_time);
        $formattedDate = $validationTime->format('d-m-Y');
        $proscat=Category::where('id',$prospect->config->category_id)->first();
        $valdate = $formattedDate;


        $today = now();
        $event=Event::where('awal_input',"<=",$today)->where('akhir_input','>=',$today)->get();

            if($role!='prj'){
                $employees = Employee::where('area', $prospect->province->prov_order_no)->orWhere('area', $prospect->province->wilayah)->orWhere('area', 'LIKE', '%' . $prospect->province->iss_area_code. '%' )->get();  
            }else{
                $employees =Employee::where('position', 'PRJ')->get();
            }    
            $employees->load("user");

                $piclist = $employees->map(function($employee){
                return[
                'user_id' => $employee ? $employee->user->id : "No User ID",

                'name' => $employee ? $employee->user->name : "Tidak ada AM/ FS bertugas di area ini"
                ];
            });

           // $piclist->push(['user_id'=>14,'name'=>'Ayane']);
                $prospect->piclist=$piclist;
                $prospect->lateinfo=$lateinfo;

                    $depopt=Department::where('id','>=',"2")->orderBy('alt_name',"asc")->get();
                    $configlist=Config::where('unit_id', $prospect->unit->id)
                    ->where('category_id', $prospect->config->category_id)
                    ->get();
                    
                    //dd($prospect->config->category_id);
                    //$peric=$prospect->personInCharge->name;
                    return response()->json([
                    'proscat'=>$proscat,
                    'prospect'=>$prospect,
                        'event'=>$event,
                        'catopt' => $cat,
                        'provopt' => $provOpt,
                        'hosopt' => $hospitals,
                        'depopt'=>$depopt,
                        'configlist'=>$configlist,
                        'sourceoption'=>$sourceoption , 
                        'valdate'=>$valdate ,
                        'bunit'=>$bunit     
                    ]);
    }





    public function validation(Prospect $prospect)
    {
        //
       $wil=substr($prospect->province->iss_area_code,0,1);
       //dd($prospect->province);
        $prospect->load("creator","hospital","review","province","department","unit","config");
        $employees = Employee::where('area', $prospect->province->iss_area_code)->orWhere('area',$wil)->get();
        
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
        $role=$user->role;
        
        $area=$user->employee->area;
        $pos=$user->employee->position;
        if($area=="HO" ){
            $provincelist=Province::all();
            }else if($role=="nsm"){
            $provincelist=Province::with('area')->where('wilayah',$area)->get();
             }else if($role==="fs"){
             $provincelist=Province::with('area')->where('prov_order_no',$area)->orWhere('iss_area_code',$area)->get();
            }else if($role==="am"){
            $areaArray = explode(',', $area);
            $provincelist=Province::with('area')->whereIn('iss_area_code', $areaArray)->get();}       
            $provOrderNos = $provincelist->pluck('prov_order_no')->toArray();





        $specialrole = ['admin','direksi','db'];

        if($area=="HO")
        {
        if(in_array($role, $specialrole)){
            $employees = Employee::all();
            $employees->load("user");
            $piclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];
            }); 
        }
        else{
            $employees = Employee::where('position',$pos)->get();
            $piclist = $employees->map(function($employee){
                return[
                'user_id' => $employee ? $employee->user->id : "No User ID",
                'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
                'area' => $employee?$employee->area:"No data "
                ];
                }); 
        }
        }
        else
        {
          if($role=="am"){
                       
             //dd($provdata)
            $employees = Employee::where('area', 'LIKE', '%' . $area . '%')->orWhereIn('area',$provOrderNos)->get();
            $employees->load("user");
            //for AM
            $amdata = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];});
            //call F
            $piclist = array_merge($amdata->toArray());


          }else{
            $employees = Employee::where('area', 'LIKE', '%' . $area . '%')->orWhereIn('area',$provOrderNos)->get();
            $employees->load("user");
            $piclist = $employees->map(function($employee){
            return[
            'user_id' => $employee ? $employee->user->id : "No User ID",
            'name' => $employee ? $employee->longname : "Tidak ada AM/ FS bertugas di area ini",
            'area' => $employee?$employee->area:"No data "
            ];
            });
          } 
         }

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

     public function infoupdate($request, $prospect)
    {
        
        $review=Review::where('prospect_id',$request->data);
        $review->update([
            "comment"=>$request->addinfo,
        ]);
        $prospect->update([
            "pic_user_id"=>$request->personincharge,
            "departmentname"=>$request->department_id,
        ]);

        return "done";
    }

    public function produpdate($request, $prospect)
    {

        $newconfig=Config::where('id',$request->productlist)->first();
        
       // dd($request);
        $nilai = ($newconfig->price_include_ppn)*(intval($request->qtyitem));
        
        $prospect->update([
            "config_id"=>$newconfig->id,
            "qty"=>intval($request->qtyitem),
            "submitted_price"=>$nilai
        ]);
        return "done";
    }


     public function promodateupdate(Request $request, Prospect $prospect)
    {
        $user=Auth::id();
        $review=Review::where('prospect_id',$prospect->id)->first();
        $send=0;
         
        $f1=$review->first_offer_date?$review->first_offer_date:"new";
        $f2=$request->first?$request->first:"new";
        $l1=$review->last_offer_date?$review->last_offer_date:"new";
        $l2=$request->last?$request->last:"new";
        $d1=$review->demo_date?$review->demo_date:"new";
        $d2=$request->demo?$request->demo:"new";
        $p1=$review->presentation_date?$review->presentation_date:"new";
        $p2=$request->presentation?$request->presentation:"new";

        if($f1==$f2){$oke="oke";}else{
                $review->update([
            'first_offer_date'=>$request->first,
           
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"first_offer_date",
                'col_before'=>$f1,
                'col_after'=>$request->first,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        }     
        
        if($l1==$l2){$oke="oke";}else{
            $review->update([
        //'first_offer_date'=>$request->first,
        'last_offer_date'=>$request->last,
       // 'demo_date'=>$request->demo,
        //'presentation_date'=>$request->presentation,
         ]);

        ReviewLog::create([
            'review_id'=>$review->id,
            'log_date'=>now(),
            'col_update'=>"last_offer_date",
            'col_before'=>$l1,
            'col_after'=>$request->last,
            'updated_by'=>$user
        ]);
        $send=$send+1;
    }     
        if($d1==$d2){$oke="oke";}else{
            $review->update([
        //'first_offer_date'=>$request->first,
        'demo_date'=>$request->demo,
       // 'demo_date'=>$request->demo,
        //'presentation_date'=>$request->presentation,
         ]);

        ReviewLog::create([
            'review_id'=>$review->id,
            'log_date'=>now(),
            'col_update'=>"demo_date",
            'col_before'=>$d1,
            'col_after'=>$request->demo,
            'updated_by'=>$user
        ]);
        $send=$send+1;
    }     
        if($p1==$p2){$oke="oke";}else{
            $review->update([
        //'first_offer_date'=>$request->first,
        'presentation_date'=>$request->presentation,
       // 'demo_date'=>$request->demo,
        //'presentation_date'=>$request->presentation,
         ]);

        ReviewLog::create([
            'review_id'=>$review->id,
            'log_date'=>now(),
            'col_update'=>"presentation_date",
            'col_before'=>$p1,
            'col_after'=>$request->presentation,
            'updated_by'=>$user
        ]);
        $send=$send+1;
    }     

        $data1='<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Terima Kasih sudah Update Review</h4>
        <h5>Refresh Jika temperature belum terupdate</h5>
        <button onClick="window.location.reload();">Refresh Page</button>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        
        <span aria-hidden="true">&times;</span>
    </button>
        </div>';
        $data='<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Terima Kasih sudah Update Review</h4>
        <h5>Refresh Jika temperature belum terupdate</h5>
        <button onClick="window.location.reload();">Refresh Page</button>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
        </div>';

        if($send>0){
        return response()->json(['success' => true,'message' => $data1]); 
        } else
        return "done";
   
   
    }
     public function chcupdate(Request $request, Prospect $prospect)
    {
        $user=Auth::id();
        $review=Review::where('prospect_id',$prospect->id)->first();
       $temper=prospectTemperature::where('prospect_id',$prospect->id)->first();
       $send=0;

        $chc2=$request->chance?$request->chance:0.2;$chc1=$review->chance?$review->chance:0.2;
        if($chc1==$chc2){$oke="oke";}else{
            $review->update([
                'chance'=>$chc2,
         ]);

        ReviewLog::create([
            'review_id'=>$review->id,
            'log_date'=>now(),
            'col_update'=>"chance",
            'col_before'=>$chc1,
            'col_after'=>$chc2,
            'updated_by'=>$user
        ]);
        $send=$send+1;
    }  

    /*
    $review->chance;
    $podate = $review->eta_po_date;
    $qty = strtotime($podate);
    $now = strtotime(now());
    $diffsec = $qty - $now;
    $diff = floor($diffsec / 86400);
    $etapodate = Carbon::parse($prospect->eta_po_date);
    $now= Carbon::now();
    $diff =$etapodate->diffInDays($now,false);
    var_dump($diff);

   
    $tempename=$temper->tempName;
    $tempecode=$temper->tempCodeName;
   
    // update temperature need to create function so i can just call it

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

    $temper->update([
        'tempName'=>$tempename,
        'tempCodeName'=>$tempecode
    ]);

    */

    $data1='<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Terima Kasih sudah Update Review Prospect</h4>
    <h5>Refresh Jika temperature belum terupdate</h5>
        <button onClick="window.location.reload();">Refresh Page</button>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
    </div>';
   

    if($send>0){
    return response()->json(['success' => true,'message' => $data1]); 
    } else
    return "done";
    
    
    }

     public function reviewupdate(Request $request, Prospect $prospect)
    {
        //return response()->json($request->demo);
        //var_dump($request->data);
        $user=Auth::id();
     $review=Review::where('prospect_id',$prospect->id)->first();
    $temper=prospectTemperature::where('prospect_id',$prospect->id)->first();
     //$prospect = Prospect::where('id',$prospect->id)->first();
    $send=0;
         $user2=$request->user_status?$request->user_status:"Belum Tahu";$user1=$review->user_status?$review->user_status:"Belum Tahu";
        $pur2=$request->purchasing_status?$request->purchasing_status:"Belum Tahu";$pur1=$review->purchasing_status?$review->purchasing_status:"Belum Tahu";
        $dir2=$request->direksi_status?$request->direksi_status:"Belum Tahu";$dir1=$review->direksi_status?$review->direksi_status:"Belum Tahu";
        $agr2=$request->anggaran_status?$request->anggaran_status:"Belum Tahu";$agr1=$review->anggaran_status?$review->anggaran_status:"Belum Tahu";
        $jns2=$request->jenis_anggaran?$request->jenis_anggaran:"Belum Tahu";$jns1=$review->jenis_anggaran?$review->jenis_anggaran:"Belum Tahu";
        
        $nac2=$request->next_action?$request->next_action:"Mapping";$nac1=$review->next_action?$review->next_action:"Mapping";
        //dd($prospect->eta_po_date);
        $eta2=$request->etapodate?$request->etapodate:"";$eta1=$review->etapodate?$prospect->eta_po_date:"";


        if($eta1==$eta2){$oke="oke";}else{
                $prospect->update([
                    'eta_po_date'=>$eta2,
             ]);

            updatelog::create([
                'prospect_id'=>$prospect->id,
                'logdate'=>now(),
                'approve_date'=>now(),
                'logdate'=>now(),
                'col_update'=>"eta_po_date",
                'col_before'=>$eta1,
                'col_after'=>$eta2,
                'request_by'=>$user,
                'approve_by'=>13,
                'req_status'=>'approve_by_system',
            ]);
            $send=$send+1;
        }     
        if($user1==$user2){$oke="oke";}else{
                $review->update([
                    'user_status'=>$user2,
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"user_status",
                'col_before'=>$user1,
                'col_after'=>$user2,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        }     

        if($pur1==$pur2){$oke="oke";}else{
                $review->update([
                    'purchasing_status'=>$pur2,
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"purchasing_status",
                'col_before'=>$pur1,
                'col_after'=>$pur2,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        } 

        if($dir1==$dir2){$oke="oke";}else{
                $review->update([
                    'direksi_status'=>$dir2,
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"direksi_status",
                'col_before'=>$dir1,
                'col_after'=>$dir2,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        }     
        if($agr1==$agr2){$oke="oke";}else{
                $review->update([
                    'anggaran_status'=>$agr2,
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"anggaran_status",
                'col_before'=>$agr1,
                'col_after'=>$agr2,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        }     

        if($jns1==$jns2){$oke="oke";}else{
                $review->update([
                    'jenis_anggaran'=>$jns2,
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"jenis_anggaran",
                'col_before'=>$jns1,
                'col_after'=>$jns2,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        }  

           
        if($nac1==$nac2){$oke="oke";}else{
                $review->update([
                    'next_action'=>$nac2,
             ]);

            ReviewLog::create([
                'review_id'=>$review->id,
                'log_date'=>now(),
                'col_update'=>"next_action",
                'col_before'=>$nac1,
                'col_after'=>$nac2,
                'updated_by'=>$user
            ]);
            $send=$send+1;
        }     
       

        $data1='<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Terima Kasih sudah Update Review Prospect</h4>
        <h5>Refresh Jika temperature belum terupdate</h5>
        <button onClick="window.location.reload();">Refresh Page</button>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
        </div>';
       

        if($send>0){
        return response()->json(['success' => true,'message' => $data1]); 
        } else
        return "done";
   
   
    }

  
     public function infoupdaterequest(Request $request, Prospect $prospect)
    {
        $user=Auth::id();
        $prospect->load("creator","hospital","review","province","department","unit","config");
       $send=0;
    
        $c=trim($prospect->review->comment);$d=trim($request->addinfo);
  
        $pic1=$prospect->pic_user_id;$pic2=$request->personincharge;
        $dpt1=$prospect->department_id;$dpt2=$request->departmentname;
    

        if($pic1==$pic2){$oke="oke";}else{
            updatelog::create([
                'prospect_id'=>$prospect->id,
                'col_update'=>'pic_user_id',
                'col_before'=>$pic1,
                'col_after'=>$pic2,
                'logdate'=>now(),
                'request_by'=>$user,
                'approve_date'=>now(),
                'approve_by'=>$user,
                'req_status'=>"system_generated"
            ]);
            $send=$send+1;
        }
        if($dpt1==$dpt2){$oke="oke";}else{
            updatelog::create([
                'prospect_id'=>$prospect->id,
                'col_update'=>'department_id',
                'col_before'=>$dpt1,
                'col_after'=>$dpt2,
                'logdate'=>now(),
                'request_by'=>$user,
                'approve_date'=>now(),
                'approve_by'=>$user,
                'req_status'=>"system_generated"
            ]);
            $send=$send+1;
        }
        if($c==$d){$oke="oke";}else{
            updatelog::create([
                'prospect_id'=>$prospect->id,
                'col_update'=>'comment',
                'col_before'=>$c,
                'col_after'=>$d,
                'logdate'=>now(),
                'request_by'=>$user,
                'approve_date'=>now(),
                'approve_by'=>$user,
                'req_status'=>"system_generated"
            ]);
            $send=$send+1;
        }

        
        
        $data='<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Berhasil Update Data</h4>
          <p>Silahkan Hubungi NSM anda untuk Proses Lebih Lanjut</p>
          <hr>
          <p class="mb-0">Jangan Lupa untuk update review Prospect.</p>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>';

        $data2='<div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Tidak ada Perubahan</h4>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
        </div>';

        
        
        if($send>0){
          $this->infoupdate($request,$prospect);
          ReviewLog::create([
            'review_id'=>$prospect->review->id,
            'log_date'=>now(),
            'col_update'=>"Change PIC / Dept",
            'col_before'=>'cekupdatelog',
            'col_after'=>'cekupdatelog',
            'updated_by'=>$user
        ]);
        return response()->json(['success' => true, 'message' => $data]);}
        else{
            return response()->json(['success' => true,'message' => $data2]); 
        }
    }


     public function produpdaterequest(Request $request, Prospect $prospect)
    {
        $user=Auth::id();
        //dd($request);
    

        $prospect->load("creator","hospital","review","province","department","unit","config");
       $cek=0;
       
        $a=$prospect->unit_id; $b=$request->editunit;

        $c=$prospect->config_id;$d=$request->productlist;

        $e=$prospect->qty;$f=$request->qtyitem;
        
        if($c==$d){$oke="oke";}else{
            updatelog::create([
                'prospect_id'=>$prospect->id,
                'col_update'=>'config_id',
                'col_before'=>$c,
                'col_after'=>$d,
                'logdate'=>now(),
                'request_by'=>$user,
                'approve_date'=>now(),
                'approve_by'=>$user,
                'req_status'=>"system_generated"
                
            ]);
            $cek=$cek+1;
        }

        if($a==$b){$oke="oke";}else{
            updatelog::create([
                'prospect_id'=>$prospect->id,
                'col_update'=>'unit_id',
                'col_before'=>$a,
                'col_after'=>$b,
                'logdate'=>now(),
                'request_by'=>$user,
                'approve_date'=>now(),
                'approve_by'=>$user,
                'req_status'=>"system_generated"
            ]);
            $cek=$cek+1;
        }

        if($e==$f){$oke="oke";}else{
            updatelog::create([
                'prospect_id'=>$prospect->id,
                'col_update'=>'qty',
                'col_before'=>$e,
                'col_after'=>$f,
                'logdate'=>now(),
                'request_by'=>$user,
                'approve_date'=>now(),
                'approve_by'=>$user,
                'req_status'=>"system_generated"
            ]);
            $cek=$cek+1;
        }

                
        $data='<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Update Berhasil</h4>
          <hr>
          <p class="mb-0">Jangan Lupa untuk update review Prospect.</p>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>';

        $data2='<div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Tidak ada Perubahan</h4>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
        </div>';

       //return response()->json($data2);
        
        if($cek>0){
            $this->produpdate($request,$prospect);
            ReviewLog::create([
                'review_id'=>$prospect->review->id,
                'log_date'=>now(),
                'col_update'=>"update product data",
                'col_before'=>'cekupdatelog',
                'col_after'=>'cekupdatelog',
                'updated_by'=>$user
            ]);
        return response()->json(['success' => true, 'message' => $data]);}
        else{
            return response()->json(['success' => true,'message' => $data2]); 
        }
    }
        
        

        
    


    public function update(Request $request, Prospect $prospect)
    {
        //
        $review=Review::where('prospect_id',$request->data);
        $options = $this->optiondata()->getData();
       
        $config=Config::where('id',$request->productlist)->first();
        
       
       //dd($options);
      
        foreach($options->anggaran->review as $aggr){
            if($aggr->id==$request->anggaranedit){
                $anggaransts=$aggr->name;
            }else $anggaransts=$request->anggaranedit;
        }
        foreach($options->anggaran->Jenis as $agjn){
            if($agjn->id==$request->jenisanggaranedit){
                $anggaranjns=$agjn->name;
            }else $anggaranjns=$request->jenisanggaranedit;
        }

        $source = intval($request->sourceedit);
        $n=$source+0;
        //var_dump($n);
        if($n>100){
            $sourceops= Event::where('id',$source)->first();
            $sourceoption=$sourceops->name;
        }else
        {
            foreach ($options->source as $option) {
                if ($option->id == $n) {
                    $sourceoption=$option->name;
                }
            }     
        }
      
        //dd($sourceoption);
        $price=$config->price_include_ppn;
        $bunit=$config->unit_id;
        $review->update([
            'anggaran_status'=>$anggaransts,
            'jenis_anggaran'=>$anggaranjns
        ]);
        $prospect->update([
            'prospect_source'=>$sourceoption,
           'province_id'=>$request->provinceedit,
           'hospital_id'=>$request->hospitallist,
           'config_id'=>$request->productlist,
           'unit_id'=>$bunit,
           'submitted_price'=>$price,
           'qty'=>$request->qtyitem,
           'department_id'=>$request->departmentname,
            'eta_po_date'=>$request->etapodateedit
        ]);
    }

    public function validationupdate(Request $request, Prospect $prospect)
    {
        //
        $role= Auth::user()->role;


        if($request->renewshow){
            $submitdate=$request->rnsubmitdate;
            $creator=$request->rnnewcreator;
            $validator=$request->rnvalidator;
            $provcode=$request->rnprovcode;
            $status=$request->renewdata;
            $personincharge=$request->rnpersonincharge;
            $id= $request->rnid;
            if($request->rnreason){
                $reason=$request->rnreason;
            }
        }else{
            $id= $request->id;
            $submitdate=$request->submitdate;
            $creator=$request->creator;
            $validator=$request->validator;         
            $provcode=$request->provcode;
            $status=$request->validation;
            $personincharge=$request->personincharge;
            if($request->reason){
                $reason=$request->reason;
            }
        }

        switch($status){
            case 404:
                rejectLog::create([
                    "prospect_id"=>$id,
                    
                    "rejected_by"=>$validator,
                    "reason"=>$reason
                ]);


            break;

            case 1:
                
                $date = Carbon::createFromFormat('Y-m-d', $submitdate);

                // Extract the year, month, and day
                $year = $date->format('y');
                $month = $date->format('m');
                $day = $date->format('d');

                // Concatenate the year, month, and day parts
                $codedate = $year . $month . $day;
                $rand3=rand(100,999);

           
                $prospect_no="ISSP-";

                if($role!="prj"){
                $prospect_no.=$provcode;
                }
                else{
                    $prospect_no.='88';  
                }
                $prospect_no.="-".$codedate."-".$rand3;
                
                if($request->renewshow){
                $prospect->update([
                    'creator'=>$creator,
                    'validation_time'=>Carbon::now(),
                    'eta_po_date'=>Carbon::now()->addDays(90),
                    'validation_by'=>$validator,
                    'prospect_no'=>$prospect_no,
                    'pic_user_id'=>$personincharge
                ]);

                }else{
                $prospect->update([
                    'validation_time'=>Carbon::now(),
                    'validation_by'=>$validator,
                    'prospect_no'=>$prospect_no,
                    'pic_user_id'=>$personincharge
                ]);
              }
            break;

        }
        
        $prospect->update([
            'status' => $status
        ]);

        //update alert
        $latealert = AlertData::with('user')->where('prospect_id',$id)->whereIn('type',['V','V3','V7','V14'])->where('status',0)
        ->update(['status'=>1]);

        $data=Prospect::with("creator","province")->where('id',$id)->get();
       
        Alert::generateAlerts($data,"R");
        AlertData::create([
            'type'=>"R",
            'prospect_id'=>$id,
            'user_id'=>$personincharge
        ]);

        
        
        
        
        if($status==404){
            return response()->json(['message' => "Data Berhasil di Reject, Silahkan Input Prospect Baru"]);
        }else{  
            if($request->renewshow){
                return response()->json(['message' => 'Berhasil Memperbaru dan Validasi Prospect,</br> dengan Nomor Prospect : <b>'.$prospect_no.'</b></br> Silahkan Melanjutkan review di Menu Prospect Review']);
            }else{
                return response()->json(['message' => 'Berhasil Validasi Prospect,</br> dengan Nomor Prospect : <b>'.$prospect_no.'</b></br> Silahkan Melanjutkan review di Menu Prospect Review']);
            }
        }
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

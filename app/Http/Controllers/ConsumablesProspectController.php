<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\consumablesProspect;
use App\Models\Hospital;
use App\Models\Config;
use App\Models\consumablesReject;
use App\Models\consumablesReview;
use App\Models\User;
use App\Models\Employee;
use App\Models\Province;
use App\Models\Department;
use App\Models\Event;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConsumablesProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.consprospect');
    }


    public function validationprospect()
    {
        return view('admin.consprospectvalidation');
    }

  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ConsumablesProspect $prospect)
    {
        //
       
        $sourceoption = $this->optiondata()->getData();
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
        $role=$user->role;
        ///$filter = prospectFilters::where('filterUser',$usid)->first();
        //$a = trim($filter->filterData, "[]");
        //$arrayfilter = explode(',', $a);
       $area=$user->employee->area;
        $pos=$user->employee->position;
        if($area=="HO" ){
        $provincelist=Province::all();
        }else if($role=="nsm"){
        $provincelist=Province::with('area')->where('wilayah',$area)->get();
         }else if($role==="fs"){
         $provincelist=Province::with('area')->where('prov_order_no',$area)->orWhere('iss_area_code',$area)->get();
        }else if($role==="am"){
        $provincelist=Province::with('area')->where('iss_area_code',$area)->get();}       
     
    
        $provOrderNos = $provincelist->pluck('prov_order_no')->toArray();
       //dd($provOrderNos);
        
        //$rumahsakit=Hospital::all();
        $dept=Department::where('id','>=',"2")->orderBy('alt_name',"asc")->get();
        $today = now();
       $event=Event::where('awal_input',"<=",$today)->where('akhir_input','>=',$today)->get();
       // $produk=Config::all();
        $bunit=Unit::all();
    //    $draft= tmpProspectInput::where("user_id",$usid)->first();
        
    //     if($draft){
    //     if($draft->hospital_id){$hodraft=Hospital::where("id",$draft->hospital_id)->first(); $data['hodraft']=$hodraft;}
    //     if($draft->hospital_id){ $catdraft=Category::where("id",$draft->category_id)->first(); $data['catdraft']=$catdraft;}
    //     if($draft->hospital_id){$configdraft=Config::where("id",$draft->config_id)->first(); $data['configdraft']=$configdraft;}
       
    //     }

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
        //$data['draft']=$draft;
        $data['pic']=$piclist;
      // $data['filter']=$arrayfilter;
       // $data['rumahsakit'] = $rumahsakit;
        $data['dept'] = $dept;
        $data['event'] = $event;
       // $data['produk'] = $produk;
        $data['source']=$sourceoption;
        $data['bunit']=$bunit;
        return response()->json($data);
    }
    public function creation()
    {
        $username = Auth::check() ? Auth::user()->name : 'Guest';

    return view('admin.consprospectcreate', ['username' => $username]);
     
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
    // Concatenate product IDs and quantities into strings for each quarter
 
    $request->validate([
        'creatorid' => 'required|numeric',
        // Add other validation rules for your other form fields if needed
    ]);


    function calculateTotalValuesForQuarter($product_ids, $quantities) {
        $config = Config::with('unit', 'brand')->whereIn('id', $product_ids)->get();
        $price_include_ppn = [];
        $values = [];
    
        foreach ($config as $index => $config1) {
            $price_include_ppn[$index] = $config1->price_include_ppn;
            $values[$index] = $price_include_ppn[$index] * $quantities[$index];
        }
    
        return $values;
    }
    $product_ids_Q1 =implode(',', $request->input('product_id_Q1'));
    $quantities_Q1 = implode(',', $request->input('qty_Q1'));
    $valueQ1 = calculateTotalValuesForQuarter($request->input('product_id_Q1'), $request->input('qty_Q1'));
    $strvalueQ1 = implode(',', $valueQ1);
    $sumvalueQ1=array_sum($valueQ1);

   // dd($sumvalueQ1);

    $product_ids_Q2 =implode(',', $request->input('product_id_Q2'));
    $quantities_Q2 = implode(',', $request->input('qty_Q2'));
    $valueQ2 = calculateTotalValuesForQuarter($request->input('product_id_Q2'), $request->input('qty_Q2'));
    $strvalueQ2 = implode(',', $valueQ2);
    $sumvalueQ2=array_sum($valueQ2);

    $product_ids_Q3 =implode(',', $request->input('product_id_Q3'));
    $quantities_Q3 = implode(',', $request->input('qty_Q3'));
    $valueQ3 = calculateTotalValuesForQuarter($request->input('product_id_Q3'), $request->input('qty_Q3'));
    $strvalueQ3 = implode(',', $valueQ3);
    $sumvalueQ3=array_sum($valueQ3);
    
    $product_ids_Q4 =implode(',', $request->input('product_id_Q4'));
    $quantities_Q4 = implode(',', $request->input('qty_Q4'));
    $valueQ4 = calculateTotalValuesForQuarter($request->input('product_id_Q4'), $request->input('qty_Q4'));
    $strvalueQ4 = implode(',', $valueQ4);
    $sumvalueQ4=array_sum($valueQ4);
   
  
    $data="done";

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
        }
        }else $anggaranjnsopt="Belum Tahu";
    }
    



    $tempcode = "CPISS - ".$request->creatorid."-".$request->cr8province.$request->cr8category .date('Y-m-d-H');



    if($sourceoption==="Event"){
        $sourceoption.=" ".$request->eventname;}
    
        if($product_ids_Q1){
        $Q1=ConsumablesProspect::create([
            'user_creator'=>$request->creatorid,
            'prospect_source'=>$sourceoption,
            'tempCode'=>$tempcode,
            'province_id'=>$request->cr8province,
            'hospital_id'=>$request->cr8hospital,
            'department_id'=>$request->cr8department,
            'config_id'=>$product_ids_Q1,
            'unit_id'=>$request->cr8bunit,
            'category_id'=>$request->cr8category,
            'submitted_total_price'=>$sumvalueQ1,
            'qty'=>$quantities_Q1,
            'po_target'=>"Q1",
             'eta_po_date'=>NULL
        ]);

        //dd($Q1->id);
        consumablesReview::create([
            'consumables_prospect_id'=>$Q1->id,
            'anggaran_status'=>$anggaranopt,
            'jenis_anggaran'=>$anggaranjnsopt,
            'comment'=>$request->cr8infoextra,
        ]);



        }
        if($product_ids_Q2){
        $Q2=ConsumablesProspect::create([
            'user_creator'=>$request->creatorid,
            'prospect_source'=>$sourceoption,
            'tempCode'=>$tempcode,
            'province_id'=>$request->cr8province,
            'hospital_id'=>$request->cr8hospital,
            'department_id'=>$request->cr8department,
            'config_id'=>$product_ids_Q2,
            'unit_id'=>$request->cr8bunit,
            'category_id'=>$request->cr8category,
            'submitted_total_price'=>$sumvalueQ2,
            'qty'=>$quantities_Q2,
            'po_target'=>"Q2",
             'eta_po_date'=>NULL
        ]);
        }
        if($product_ids_Q3){
        $Q3=ConsumablesProspect::create([
            'user_creator'=>$request->creatorid,
            'prospect_source'=>$sourceoption,
            'tempCode'=>$tempcode,
            'province_id'=>$request->cr8province,
            'hospital_id'=>$request->cr8hospital,
            'department_id'=>$request->cr8department,
            'config_id'=>$product_ids_Q3,
            'unit_id'=>$request->cr8bunit,
            'category_id'=>$request->cr8category,
            'submitted_total_price'=>$sumvalueQ3,
            'qty'=>$quantities_Q3,
            'po_target'=>"Q3",
             'eta_po_date'=>NULL
        ]);
        }
        if($product_ids_Q4){
        $Q4=ConsumablesProspect::create([
            'user_creator'=>$request->creatorid,
            'prospect_source'=>$sourceoption,
            'tempCode'=>$tempcode,
            'province_id'=>$request->cr8province,
            'hospital_id'=>$request->cr8hospital,
            'department_id'=>$request->cr8department,
            'config_id'=>$product_ids_Q4,
            'unit_id'=>$request->cr8bunit,
            'category_id'=>$request->cr8category,
            'submitted_total_price'=>$sumvalueQ4,
            'qty'=>$quantities_Q4,
            'po_target'=>"Q4",
             'eta_po_date'=>NULL
        ]);
        }





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\consumablesProspect  $consumablesProspect
     * @return \Illuminate\Http\Response
     */

    private function tableQuarter($qty, $config_id)
    {
        $arrayitems = explode(',', $config_id);
        $arrayqty = explode(',', $qty);
        $countitems = count($arrayitems);

        $data = "<div class='container-fluid'>
                    <H4>List Item </H4>
                    <div class='table-responsive'>
                     <table class='table'>
                        <thead>
                            <tr>
                                <th scope='col'>Consumable Item</th>
                                <th scope='col'>Qty</th>
                                <th scope='col'>Value</th>
                            </tr>
                        </thead>
                        <tbody>";

        $totalValue = 0;

        for ($i = 0; $i < $countitems; $i++) {
            $itemdata = Config::where("id", $arrayitems[$i])->first();
            if ($itemdata) {
                $itemName = $itemdata->name;
                $itemQty = $arrayqty[$i];
                $itemValue = $itemdata->price_include_ppn * $itemQty;
                $totalValue += $itemValue;
                $formattedItemValue =  "Rp" . number_format($itemValue, 0, ',', '.') . ",-";

                $data .= "<tr>
                            <td>{$itemName}</td>
                            <td>{$itemQty}</td>
                            <td>{$formattedItemValue}</td>
                          </tr>";
            }
        }

        $formattedTotalValue = "Rp". number_format($totalValue, 0, ',', '.') . ",-";


        $data .= "<tr>
                    <td colspan='2' style='text-align: right; font-weight: bold;'>Total Quarter Value </td>
                <td style='font-weight: bold;'>{$formattedTotalValue}</td
                  </tr>
                  </tbody>
                  </table>
                  </div>
              </div>";

    
        

        return $data;
    }

    public function show(consumablesProspect $consumablesProspect)
    {
        //
        $nop= $consumablesProspect -> prospect_no;
        
        $allp = consumablesProspect::with('province','hospital','creator','personInCharge','department','unit','category')->where("prospect_no",$nop)->orderBy('po_target',"asc")->get();
        
        //dd($allp[0]);

        for ($i = 0; $i < 4; $i++) {
            $x = $i + 1;
            $n = "Q" . $x;
            $found = false;
        
            foreach ($allp as $prospect) {
                if ($n == $prospect->po_target) {
                    $showdata[$i] = $this->tableQuarter($prospect->qty, $prospect->config_id);
                    $found = true;
                    
                    switch($prospect->status)
                    {
                        case -1 :
                            $statusdata[$i]='<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-secondary">MISSED</span></a></h5>';
                            break;
                        case 0 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-info">WAIT APPROVAL</span></a></h5>';
                            break;
                        case 1 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-primary">NEW</span></a></h5>';
                            break;
                        case 2 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-warning">CONSIGN</span></a> | '.Carbon::parse($prospect->consignDrop)->format('d M Y').'</h5>';
                            break;
                        case 3 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-info">READY STOCK</span></a></h5>';
                            break;
                        case 4 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-secondary">WAIT STOCK / ORDERING STOCK</span></a></h5>';
                            break;
                        case 404 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-danger">REJECT</span></a></h5>';
                            break;
                        case 99 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-dark">EXPIRED</span></a></h5>';
                            break;
                        case 77 :
                            $statusdata[$i] = '<h5><a href="javascript:void(0)" id="' . $prospect->id . '" class="btn-sts"><span class="badge badge-succes">DONE / SUCCESS</span></a></h5>';
                            break;
                    }

                    break; // Exit the loop once the data for the quarter is found
                }
            }
        
            if (!$found) {
                $showdata[$i] = "Tidak Ada Prospect di Quarter Ini";
                $statusdata[$i]= "";
            }

        }



        $coredata=['hospital'=>$allp[0]->hospital->name,
                    'creator'=>$allp[0]->creator->name,  
                    'department'=>$allp[0]->department->name,  
                    'personInCharge'=>$allp[0]->personInCharge->name,  
                    'unit'=>$allp[0]->unit->name,  
                    'category'=>$allp[0]->category->name,  
                      
    ];
       
        return view('admin.prospectdetail',compact(['allp','coredata','showdata','statusdata']));
    }

    public function detaildata(consumablesProspect $consumablesProspect)
    {
        //
        $nop= $consumablesProspect -> tempCode;
        
        $allp = consumablesProspect::with('province','hospital','creator','personInCharge','department','unit','category')->where("tempCode",$nop)->orderBy('po_target',"asc")->get();
        
        //dd($allp[0]);

        for ($i = 0; $i < 4; $i++) {
            $x = $i + 1;
            $n = "Q" . $x;
            $found = false;
        
            foreach ($allp as $prospect) {
                if ($n == $prospect->po_target) {
                    $showdata[$i] = $this->tableQuarter($prospect->qty, $prospect->config_id);
                    $found = true;
                    switch($prospect->status)
                    {
                        case -1 :
                            $statusdata[$i]="<h5><span class='badge badge-secondary'>MISSED</span></h5>";
                            break;
                        case 0 :
                            $statusdata[$i] = "<h5><span class='badge badge-info'>WAIT APPROVAL</span></h5>";
                            break;
                        case 1 :
                            $statusdata[$i] = "<h5><span class='badge badge-primary'>NEW</span></h5>";
                            break;
                        case 2 :
                            $statusdata[$i] = "<h5><span class='badge badge-warning'>CONSIGN</span></h5>";
                            break;
                        case 3 :
                            $statusdata[$i] = "<h5><span class='badge badge-info'>READY STOCK</span></h5>";
                            break;
                        case 4 :
                            $statusdata[$i] = "<h5><span class='badge badge-secondary'>WAIT STOCK / ORDERING STOCK</span></h5>";
                            break;
                        case 404 :
                            $statusdata[$i] = "<h5><span class='badge badge-danger'>REJECT</span></h5>";
                            break;
                        case 99 :
                            $statusdata[$i] = "<h5><span class='badge badge-dark'>EXPIRED</span></h5>";
                            break;
                        case 77 :
                            $statusdata[$i] = "<h5><span class='badge badge-succes'>DONE / SUCCESS</span></h5>";
                            break;
                    }
                    $actiondata[$i]=  '<a href="javascript:void(0)" id="' . $prospect->id . '" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-edit">Edit Product</a>' ;
                    $actiondata[$i].=  '<a href="javascript:void(0)" id="' . $prospect->id . '" class="btn aksi btnaksi btn-warning btn-sm ml-2  btn-chq">Pindah Quarter</a>' ;
                    $actiondata[$i].=  '<a href="javascript:void(0)" id="' . $prospect->id . '" class="btn aksi btnaksi btn-danger btn-sm ml-2  btn-delete">Hapus Product</a>' ;
                    
                    break; // Exit the loop once the data for the quarter is found
                }
            }
            
            if (!$found) {
                $showdata[$i] = "Tidak Ada Prospect di Quarter Ini";
                $statusdata[$i]= "";
                $actiondata[$i]=  '<a href="javascript:void(0)" id="' . $prospect->id . '" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-addP">Tambah Product</a>' ;
               
            }

        }



        $coredata=['hospital'=>$allp[0]->hospital->name,
                    'creator'=>$allp[0]->creator->name,  
                    'department'=>$allp[0]->department->name,  
                    //'personInCharge'=>$allp[0]->personInCharge->name,  
                    'dataid'=>$allp[0]->id,
                    'unit'=>$allp[0]->unit->name,  
                    'category'=>$allp[0]->category->name,  
                      
    ];
       
        return view('admin.prospectdetail2',compact(['allp','coredata','showdata','statusdata','actiondata']));
    }


    public function validationproddetail(consumablesProspect $consumablesProspect)
    {
        //
        $nop= $consumablesProspect -> tempCode;
        
        $allp = consumablesProspect::with('hospital','creator','personInCharge','department','unit','category')->where("tempCode",$nop)->orderBy('po_target',"asc")->get();
        
        //dd($allp[0]);
        $showdata = [];

        for ($i = 0; $i < 4; $i++) {
            $x = $i + 1;
            $n = "Q" . $x;
            $found = false;
    
            foreach ($allp as $prospect) {
                if ($n == $prospect->po_target) {
                    $showdata[$i] = $this->tableQuarter($prospect->qty, $prospect->config_id);
                    $found = true;
                    break;
                }
            }
    
            if (!$found) {
                $showdata[$i] = "Tidak Ada Prospect di Quarter Ini";
            }
        }
    
        $coredata = [
            'hospital' => $allp[0]->hospital->name,
            'creator' => $allp[0]->creator->name,
            'department' => $allp[0]->department->name,
            'created_at' => $allp[0]->created_at->format('d-M-Y'), 
           
            'unit' => $allp[0]->unit->name,
            'category' => $allp[0]->category->name,
        ];
    
        return response()->json([
            'coredata' => $coredata,
            'showdata' => $showdata
        ]);
    }


    

    public function validation(consumablesProspect $consumablesProspect)
    {
        //
        $wil=substr($consumablesProspect->province->iss_area_code,0,1);
        //dd($consumablesProspect);
        $consumablesProspect->load("Cat","creator","hospital","review","province","department","unit");
       // dd($consumablesProspect);
        $employees = Employee::where('area', $consumablesProspect->province->iss_area_code)->orWhere('area',$wil)->get();
        
        $usid=Auth::user()->id;
        $user=User::with('employee')->where('id',$usid)->first();
        $role=$user->role;
        
        $area=$user->employee->area;
        $pos=$user->employee->position;
        if($area=="HO"){
        $provincelist=Province::all();
        }else if($role=="nsm"){
        $provincelist=Province::with('area')->where('wilayah',$area)->orWhere('wilayah')->get();
        }else if($role==="am"){
        $provincelist=Province::with('area')->where('iss_area_code',$area)->get();       
    
        }else if($role==="fs")
        $provincelist=Province::with('area')->where('prov_order_no',$area)->get();       
    
        $provOrderNos = $provincelist->pluck('prov_order_no')->toArray();
       //dd($provOrderNos);





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

        $consumablesProspect->piclist=$piclist;
     
        return response()->json($consumablesProspect);
    }



    public function validationupdate(Request $request, consumablesProspect $consumablesProspect)
    {
        //
        $tmpcd =$consumablesProspect ->tempCode;

        $categoryId= $consumablesProspect ->category_id;
        
        $cpdata = consumablesProspect::where('tempCode',$tmpcd)->get();

      //  dd($cpdata);



        $cek=$request->input('validation', 99);
        switch($cek){
            case 404:
                foreach($cpdata as $conspros){
                consumablesReject::create([
                    "consumables_prospects_id"=>$conspros->id,
                    
                    "rejected_by"=>$request->validator,
                    "reason"=>$request->reason
                ]);
                }

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

                $prospect_no="ISCP-";
                $prospect_no.=$request->provcode;
                $prospect_no.="-".$codedate."/".$categoryId."-".$rand3;
                
                foreach($cpdata as $conspros){
                $conspros->update([
                        'validation_time'=>Carbon::now(),
                        'validation_by'=>$request->validator,
                        'prospect_no'=>$prospect_no,
                        'pic_user_id'=>$request->personincharge,
                        'status' => $cek
                    ]);
                }
            break;

        }
    

           
        if($cek==404){
            return response()->json(['message' => "Data Berhasil di Reject, Silahkan Input Prospect Baru"]);
        }else{   
            return response()->json(['message' => 'Berhasil Validasi Prospect,</br> dengan Nomor Prospect : <b>'.$prospect_no.'</b></br> Silahkan Melanjutkan review di Menu Prospect Review']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\consumablesProspect  $consumablesProspect
     * @return \Illuminate\Http\Response
     */
    public function edit(consumablesProspect $consumablesProspect)
    {
        //
        
        $consumablesProspect->load("Cat","creator","hospital","review","province","department","unit");
        $provOpt= Province::all();
        $bunit=Unit::all();
        $cat=Category::all();
        $hospitals = Hospital::where('province_id', $consumablesProspect->province_id)->get();
        $sourceoption = $this->optiondata()->getData();
        //$lateinfo=$consumablesProspect->review->comment?$consumablesProspect->review->comment:"Silahkan Update info";
        $validationTime = Carbon::parse($consumablesProspect->validation_time);
        $formattedDate = $validationTime->format('d-m-Y');
        $proscat=Category::where('id',$consumablesProspect->category_id)->first();
        $valdate = $formattedDate;


        $today = now();
        $event=Event::where('awal_input',"<=",$today)->where('akhir_input','>=',$today)->get();

            $employees = Employee::where('area', $consumablesProspect->province->prov_order_no)->orWhere('area', $consumablesProspect->province->wilayah)->orWhere('area', 'LIKE', '%' . $consumablesProspect->province->iss_area_code. '%' )->get();  
                    $employees->load("user");

                $piclist = $employees->map(function($employee){
                return[
                'user_id' => $employee ? $employee->user->id : "No User ID",

                'name' => $employee ? $employee->user->name : "Tidak ada AM/ FS bertugas di area ini"
                ];
            });

           // $piclist->push(['user_id'=>14,'name'=>'Ayane']);
                $consumablesProspect->piclist=$piclist;
                //$consumablesProspect->lateinfo=$lateinfo;

                    $depopt=Department::where('id','>=',"2")->orderBy('alt_name',"asc")->get();
                    $configlist=Config::where('unit_id', $consumablesProspect->unit->id)
                    ->where('category_id', $consumablesProspect->category_id)
                    ->get();
                    
                    //dd($prospect->config->category_id);
                    //$peric=$prospect->personInCharge->name;
                    return response()->json([ 
                    'proscat'=>$proscat,
                    'prospect'=>$consumablesProspect,
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\consumablesProspect  $consumablesProspect
     * @return \Illuminate\Http\Response
     */
    
    public function updatests(Request $request, consumablesProspect $consumablesProspect)
    {
        $consumablesProspect->update([
            'status'=>$request->input('status'),
            'consignDrop'=>$request->input('dateconsign')    
        ]);
        return "done";
    }   

    public function update(Request $request, consumablesProspect $consumablesProspect)
    {
         
    
        function calculateTotalValues($product_ids, $quantities) {
            $config = Config::with('unit', 'brand')->whereIn('id', $product_ids)->get();
            $price_include_ppn = [];
            $values = [];
        
            foreach ($config as $index => $config1) {
                $price_include_ppn[$index] = $config1->price_include_ppn;
                $values[$index] = $price_include_ppn[$index] * $quantities[$index];
            }
        
            return $values;
        }
        $product_ids =implode(',', $request->input('product_id'));
        $quantities = implode(',', $request->input('qty'));
        $value = calculateTotalValues($request->input('product_id'), $request->input('qty'));
        $strvalue = implode(',', $value);
        $sumvalue=array_sum($value);

        $consumablesProspect->update([
            
            'config_id'=>$product_ids,       
            'submitted_total_price'=>$sumvalue,
            'qty'=>$quantities,
        ]);

        return "done";



    }

    public function rsupdate(Request $request, consumablesProspect $consumablesProspect)
    {
        
        $data = consumablesProspect::where('tempCode',$consumablesProspect->tempCode)->get();
        //dd($data);
        foreach ($data as $cpros ) {
            $cpros->update([
                'hospital_id'=>$request->RS,
                'department_id'=>$request->dept,
            ]); 
        
        };

        return "done";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\consumablesProspect  $consumablesProspect
     * @return \Illuminate\Http\Response
     */
    public function destroy(consumablesProspect $consumablesProspect)
    {
        //
    }

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
                $dataoption['conStatus'] = array(
                   ['id'=>1,
                    "name" => "New",],
                   ['id'=>2,
                    "name" => "Consign",],
                   ['id'=>3,
                    "name" => "Ready Stock",],
                   ['id'=>4,
                    "name" => "Wait Stock",],
                   
                   [ 'id'=>77,
                    "name" =>   "Success",
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
}

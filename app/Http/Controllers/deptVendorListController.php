<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\deptVendorList;
use App\Models\vendorForecast;
use App\Models\Config;
use App\Models\Hospital;
use App\Models\Province;
use App\Models\User;
use App\Models\Employee;
use DataTables;


class deptVendorListController extends Controller
{
    //

    public function index()
    {
        //
        return view('admin.buyerList');
    }

    public function data()
    {
        $prv = deptVendorList::with('hospital','department')->get();
       
        return DataTables::of($prv)

            ->addColumn('action', function ($prv) {
                $theroutes = route('buyerforecast', ['deptVendorList' => $prv->id]);
                $btn = '<div class="row"><a href="'.$theroutes.'" class="btn btn-primary btn-sm ml-2 btn-detail">Detail</a>';
                //$btn .= '<a href="javascript:void(0)" id="' . $hpital->id . '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
                return $btn;
            })
            ->addColumn('provname',function($prv){
                $provid= $prv->hospital->province_id;
                $province = Province::where('id',$provid)->first();
                $prvname = $province->name;
                return $prvname;
            })
            ->addColumn('vendStatus',function($prv){
                $vendStatus= $prv->status;
                switch ($vendStatus) {
                    case (1):
                        $data="Active";
                        break;
                    case (0):
                        $data="Inactive";
                        break;
                }
                
                return $data;
            })

           
            ->rawColumns(['action'])
            ->toJson();

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
    public function show(deptVendorList $deptVendorList)
    {
        //
    }

    public function edit(deptVendorList $deptVendorList)
    {
        //
    }
    public function update(Request $request,deptVendorList $deptVendorList)
    {
        //
    }

    public function Forecast(deptVendorList $deptVendorList)
    {
       // dd($deptVendorList);
        $vendorlist= $deptVendorList;
        return view('admin.buyerForecast', compact('vendorlist'));
           
    }

    public function ForecastData(Request $request)
    {  
    
        function budataCons($bu)
        {
            $result = new \stdClass();

            switch ($bu) {
                case 'BUICU':
                    $result->name = 'ICU/Anesthesia';
                    $result->id = 2;
                    break;
                case 'BUCV':
                    $result->name = 'CARDIOVASCULAR';
                    $result->id = 3;
                    break;
                case 'BUSWP':
                    $result->name = 'Sarana OK';
                    $result->id = 4;
                    break;
                case 'BUESU':
                    $result->name = 'Electrosurgery';
                    $result->id = 5;
                    break;
                case 'BUURO':
                    $result->name = 'URO Imaging';
                    $result->id = 6;
                    break;
                case 'BUMOT':
                    $result->name = 'MOT';
                    $result->id = 7;
                    break;
                case 'BUINS':
                    $result->name = 'Instrument';
                    $result->id = 8;
                    break;
                case 'BURT':
                    $result->name = 'RT Onco';
                    $result->id = 9;
                    break;
                default:
                    $result->name = "BUKAN BU Indosopha";
                    $result->id = 404;
                    break;
            }

            return $result;
        }


        function userutilCons()
        {
            $user = auth()->user();
            $usid = $user->id;
            $roles = $user->role;
            $imnow = User::with('employee')->where('id', $usid)->first();
            $area = $imnow->employee->area;
            $position = $imnow->employee->position;
            $buname = budataCons($position)->name;
            $buid = budataCons($position)->id;
            return [
                'user' => $user,
                'usid' => $usid,
                'roles' => $roles,
                'imnow' => $imnow,
                'area' => $area,
                'position' => $position,
                'buname' => $buname,
                'buid' => $buid,

            ];
        }
        $data = userutilCons();

        function getam($isi, $isi2)
        {
            $am = Employee::with('user')->where([['area', $isi], ['position', 'AM']])->get();

            $nsm = Employee::with('user')->where([['area', $isi2], ['position', 'NSM']])->get();

            $result = [];

            if ($am->count() > 0) {
                $useram = $am->pluck('user.name');

                $useramid = $am->pluck('user.id');
                $arr1 = json_decode($useramid);
                $array = json_decode($useram);
                $result['amid'] = $arr1[0];
                $result['am'] = $array[0];
            } else {
                $result['am'] = "0";
            }

            if ($nsm->count() > 0) {
                $usernsm = $nsm->pluck('user.name');
                $usernsmid = $nsm->pluck('user.id');
                $arr1 = json_decode($usernsmid);
                $array = json_decode($usernsm);
                $result['nsmid'] = $arr1[0];
                $result['nsm'] = $array[0];
            } else {
                $result['nsm'] = "0";
            }

            return $result;
        };

       
        $area = $data['area'];

            switch ($data['roles']) {
                case "admin":
                    $prv = vendorForecast::with("creator","Cat","province", "hospital", "department", "unit")
                    ->where("hospital_id",$request->hospital_data)
                    ->where("department_id",$request->department_data)
                  
                    ->get();

                  
                    break;

                case "bu":
             
                    break;


                case "fs":
            
                    break;

                case "am":
           
                    break;

                case "nsm":
                 
            }
    


        $dataTable =  DataTables::of($prv)
            ->addIndexColumn()
            ->addColumn('creatorname', function ($prp) {
                $dt = $prp->created_at->format('d-M-Y');
                $creator = $prp->creator->name ? $prp->creator->name :'missing data creator';
            
                $comdata = "<i>" . $creator . "</i></br>" . $dt;
               
                return $comdata;
            })
          
            ->addColumn('catdata', function ($prp) {
                
                $catdata = $prp->Cat->name;

               $data= '<div class="row mt-1 mr-1"><a href="javascript:void(0)" id="' . $prp->id . '" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-vdet">'.$catdata.'</a></div>';
                return $data;
            })
            ->addColumn('provincedata', function ($prp) {

                $newprov = $prp->province ? $prp->province->name : 'No Province detected';

                $AM = getam($prp->province->iss_area_code, $prp->province->wilayah);


                if ($AM['am'] != "0") {
                    $newdata = $newprov . "</br>" . $AM['am'];
                } else 
                if ($AM['nsm'] != "0") {
                    $newdata = $newprov . "</br>" . $AM['nsm'];
                } else
                    $newdata = $newprov . "</br>Tidak ada AM/NSM";
                return $newdata;
            })
            ->addColumn('hospitaldata', function ($prp) {
                $dep = $prp->department->name;
                $host = $prp->hospital->name;
                $target = $prp->hospital->target;
            
                if($target=="Key Account" || $target =="Prioritas"){
                    $show="<div class='text-success'>".$target."</div>";
                    $muncul = $host . "</br>" . $show. "</br></br>" . $dep;
                }else{
                $muncul = $host . "</br>" . $target. "</br></br>" . $dep;}
               
                return $muncul;
            })
            ->addColumn('ProvOrderNo', function ($prp) {

                $newprov = $prp->province ? $prp->province->prov_order_no : 'No Province detected';
                return $newprov;
           
            })
            ->addColumn("Items",function($prp){
                $item = $prp->config_id;
                $arrayitems = explode(',', $item);
                $qty = $prp->qty;
                $arrayqty = explode(',', $qty);
                $data = "";
                $countitems = count($arrayitems);
                // Generate a unique ID for the collapse component
                $collapseId = "collapse_" . $prp->id;
            
                $data .= "<button class='btn btn-primary' type='button' data-toggle='collapse' data-target='#$collapseId' aria-expanded='false' aria-controls='$collapseId'>".$countitems." Produk</button>";
                $data .= "<div class='collapse' id='$collapseId'><div class='card card-body'>";
            
                for ($i = 0; $i < count($arrayitems); $i++) {
                    $itemdata = Config::where("id", $arrayitems[$i])->first();
                    $data .= "<div>" . $itemdata->name ."</br>(".$arrayqty[$i]." ".$itemdata->uom. ")</div></br>";
                }
            
                $data .= "</div></div>";
            
                return $data;
            })

            ->addColumn('valuetotal', function ($prp) {
         
                $price = $prp->submitted_total_price;
             
                $rupiah = number_format($price, 0, ',-', '.');
                return $rupiah;
            })
        
            ->addColumn("po_target", function($prp) {
                
                $data = $prp->status;
                switch($data){
                    case 0:
                        return "<h5><span class='badge badge-info'>".$prp->po_target."</span></h5>";
                        break;
                    case 77:
                        return "<h5><span class='badge badge-success'>".$prp->po_target."</span></h5>";
                        break;
                     case 404 :
                        return "<h5><span class='badge badge-danger'>".$prp->po_target."</span></h5>";
                        break;
                    case 99 :
                        return "<h5><span class='badge badge-dark'>".$prp->po_target."</span></h5>";
                        break;
                    
                    }
            })


            ->addColumn("status", function($prp) {
                $data = $prp->status;

                switch($data){
                    case 404 :
                        return "<h5><span class='badge badge-secondary'>MISSED</span></h5>";
                        break;
                    case 0 :
                        return "<h5><span class='badge badge-info'>WAIT ORDER</span></h5>";
                        break;
                    case 99 :
                        return "<h5><span class='badge badge-dark'>EXPIRED</span></h5>";
                        break;
                    case 77 :
                        return "<h5><span class='badge badge-succes'>DONE / SUCCESS</span></h5>";
                        break;
                 }


                
            })

            ->addColumn('action', function ($prp) {
                $routesdetail ="#";
                $theroutes = route('admin.cnprospectdetail', ['consumablesProspect' => $prp->id]);
                switch ($prp->status) {
                    case -1:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        $btn = "<div class='d-inline-flex align-items-center'><a href='$routesdetail' ><h5><span class='badge bg-primary text-light'>Detail Data</span></h5></a></div>";
                        return $btn;
                        break;
                
                    
                    case (99):
                        $btn = '<div class="row"><a xid(0)" id="' . $prp->id . '" class="btn btnaksi btn-warning aksi btn-sm ml-2 btn-renew">Renew</a>';
                        return $btn;
                        break;
                    case (404):
                        $btn = "Reject Karena " . $prp->rejection->reason;
                        return $btn;
                        break;
                    default:

                        $btn = '<div class="row"><a href="javascript:void(0)" id="' . $prp->id . '" class="btn aksi btnaksi btn-warning btn-sm ml-2 btn-validasi">Approval</a></div>';
                        $btn .= '<div class="row mt-1 mr-1"><a href='.$theroutes.' class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-edt">Edit</a></div>';

                        return $btn;
                }
            })

            ->addColumn('cnpropdetail', function ($prp) {
                $no = $prp->prospect_no;
                $theroutes = route('admin.cnprospecteditdata', ['consumablesProspect' => $prp->id]);
                

                $data = "<a href=$theroutes onclick=''><h5><span class='badge prpno bg-info text-light'>$no</span></h5></a>";
                /*$data.="<a class='nav-link dropdown-toggle' href='#' id='alertsDropdown'role='button'
                data-toggle=' aria-haspopup='true' aria-expanded='>
                <i class='fas fa-bell fa-fw'></i>
                <!-- Counter - Alerts -->
                <span class='badge badge-danger badge-counter' id='remarksCount'>$remarksCount</span>
            </a>";*/
                //$data.="</br>$prp->prospect_source";
                
                
                
                return $data;
            })
            ->addColumn('result_po',function($prp){
                $data = $prp->result?? $prp->result->po_target??"belom po";
                return $data;

            })
            ->addColumn('result_qty',function($prp){
                $data = $prp->result?? $prp->result->po_target??"belom po";
                return $data;

            })
            ->addColumn('result_value',function($prp){
                $data = $prp->result?? $prp->result->po_target??"belom po";
                return $data;

            })
            ->addColumn('result_config_id',function($prp){
                $data = $prp->result?? $prp->result->po_target??"belom po";
                return $data;

            })
            ->addColumn('result_config_id',function($prp){
                $data = $prp->result?? $prp->result->po_target??"belom po";
                return $data;

            })




            //->only(['id','creator','personInCharge',"prospect_no"])
            ->rawColumns(["creatorname","cnpropdetail","action","status","po_target","valuetotal","Items","hospitaldata","provincedata","catdata"])

            ->toJson([]);

        $utils = userutilCons();
        $data = $dataTable->original;
        //$dcollect=collect($data)->sortByDesc('temperid');
       // dd($dcollect);
        //$jsondata=json_encode($data);
        //dd($jsondata);
        $data['additionalData'] = [
            'user' => $utils['user'],
            'usid' => $utils['usid'],
            'roles' => $utils['roles'],
            'imnow' => $utils['imnow'],
            'area' => $utils['area'],
            'position' => $utils['position'],
            'buname' => $utils['buname'],
            'buid' => $utils['buid'],
        ];

        return response()->json($data);
    }
}





<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use Illuminate\Support\HtmlString;
use App\Models\Hospital;
use App\Models\Config;
use App\Models\Category;
use App\Models\Prospect;
use App\Models\ConsumablesProspect;
use App\Models\Alert;
use App\Models\Brand;
use App\Models\ReviewLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

use DateTime;
use Yajra\DataTables\Contracts\DataTable;

class DataCompileController extends Controller
{

    public function getData()
    {

        $employ = Employee::with('user')->get();

        return DataTables::of($employ)


            ->addColumn('action', function ($employee) {
                $btn = '<div class="row"><a href="javascript:void(0)" id="' . $employee->id . '" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                $btn .= '<a href="javascript:void(0)" id="' . $employee->id . '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

                return $btn;
            })
            ->addColumn('action2', function ($employee) {

                $btn2 = $employee->user->role;
                $btn2 .= ' <div class="row"><a href="javascript:void(0)" id="' . $employee->user->id . '" class="btn btn-primary btn-sm ml-2 btn-editrole">Edit Role</a>';
                $btn2 .= ' <a href="javascript:void(0)" id="' . $employee->user->id . '" class="btn btn-danger btn-sm ml-2 btn-clear">Set To User</a></div>';
                $btnx = new HtmlString($btn2);
                return $btnx;
            })

            ->addColumn('role', function ($employee) {
                return $employee->user->role ?? "N/A";
            })
            ->addColumn('userid', function ($employee) {
                return $employee->user->id ?? "N/A";
            })


            ->toJson();
    }


    public function CreatedData()
    {

        $user = auth()->user();

        $usrole = $user->role;

        $usid = $user->id;

        $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks")
            ->where("status", '!=', 1)
            ->where("user_creator", $usid)
            ->orderBy('status', 'ASC')
            ->orderBy("id", 'DESC')
            ->get();

        return DataTables::of($prv)
            ->addIndexColumn()
            ->addColumn('submitdate', function ($prp) {
                $dt = $prp->created_at->format('d-M-Y');
                return $dt;
            })
            ->addColumn('hospital', function ($prp) {
                $dep = $prp->department->name;
                $host = $prp->hospital->name;
                $muncul = $host . "</br>" . $dep;
                return $muncul;
            })
            ->addColumn('price', function ($prp) {

                $rupiah = number_format($prp->config->price_include_ppn, 0, ',-', '.');
                return $rupiah;
            })
            ->addColumn('statsname', function ($prp) {
                switch ($prp->status) {


                    case (1):
                        $tampil = "<H4><span class='badge stts bg-success text-light'>VALID</span></H4>";
                        return $tampil;
                        break;
                    case (404):
                        $tampil = "<H4><span class='badge stts bg-danger text-light'>REJECT</span></H4>";
                        return $tampil;
                        break;
                    case (99):
                        $tampil = "<H4><span class='badge stts bg-dark text-light'>EXPIRED</span></H4>";
                        return $tampil;
                        break;

                    case (0):
                        $tampil = "<H4><span class='badge stts bg-info text-light'>NEW</span></H4>";
                        return $tampil;
                }
            })
            ->addColumn('action', function ($prp) {

                $editform = route('admin.prospectedit', $prp);
                $validasi = route('admin.prospectvalidation', $prp);

                switch ($prp->status) {
                    case (0):
                        $btn = '<div class="row"><a xid(0)" id="' . $prp->id . '" class="btn btnaksi btn-info aksi btn-sm ml-2 btn-reminder">Reminder</a>';
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

                        $btn = "";
                        if (auth()->user()->role != 'fs') {
                            $btn .= '<div class="row"><a href="javascript:void(0)" id="' . $prp->id . '" class="btn aksi btnaksi btn-warning btn-sm ml-2 btn-validasi">Approval</a></div>';
                        }

                        $btn .= '<div class="row mt-1 mr-1"><a href="javascript:void(0)" id="' . $prp->id . '" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-edit">Edit</a></div>';

                        return $btn;
                }
            })
            ->rawColumns(['hospital', 'statsname', "action"])

            ->toJson();
    }


    public function getProductDetail(Request $request)
    {

        $detailselected = Config::where('name', $request->product);
        return response()->json($detailselected);
    }



    public function HospitalData()
    {

        $prv = Hospital::with('province')->get();

        return DataTables::of($prv)


            ->addColumn('action', function ($hpital) {
                $btn = '<div class="row"><a href="javascript:void(0)" id="' . $hpital->id . '" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                //$btn .= '<a href="javascript:void(0)" id="' . $hpital->id . '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

                return $btn;
            })

            ->addColumn('provname', function ($hpital) {
                return $hpital->province->name;
            })
            ->addColumn('prov_orderno', function ($hpital) {
                return $hpital->province->prov_order_no;
            })
            ->only(['id', 'code', 'name', 'action', 'category', 'city', 'address', 'ownership', 'owned_by', 'class', 'akreditas', 'target', 'provname', 'prov_orderno'])
            ->rawColumns(['action'])
            ->toJson();
    }

    public function ConfigData()
    {

        $cnf = Config::with('unit','brand','category')->get();

        return DataTables::of($cnf)

            ->addColumn('action', function ($cfg) {
                $btn = '<div class="row"><a href="javascript:void(0)" id="' . $cfg->id . '" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                //$btn .= '<a href="javascript:void(0)" id="' . $cfg->id . '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

                return $btn;
            })

     
            ->addColumn('pformat', function ($cfg) {
                $pformat = number_format($cfg->price_include_ppn);
                return $pformat;
            })
            ->rawColumns(['action'])
            ->toJson();
    }





    public function ProspectData(Request $request)
    {
        function reviewcolor($isi)
        {
            switch ($isi) {
                case ("POSITIF"):
                    return "bg-success";
                    break;
                case ("NEUTRAL"):
                    return "bg-warning";
                    break;
                case ("SETUJU"):
                    return "bg-primary";
                    break;
                default:
                    return "bg-danger";
            }
        }

        function budata($bu)
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


        function userutil()
        {
            $user = auth()->user();
            $usid = $user->id;
            $roles = $user->role;
            $imnow = User::with('employee')->where('id', $usid)->first();
            $area = $imnow->employee->area;
            $position = $imnow->employee->position;
            $buname = budata($position)->name;
            $buid = budata($position)->id;
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
        $data = userutil();

        function getareaman($isi, $isi2)
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

        function generateAlerts($prospects, $alertType)
        {
            foreach ($prospects as $prospect) {
                $userdata = getareaman($prospect->province->iss_area_code, $prospect->province->wilayah);

                $existingAlert = Alert::where('prospect_id', $prospect->id)->where('type', $alertType)->first();

                // Create alert if an appropriate alert type is generated
                if (!$existingAlert) {
                    if ($userdata['am'] != "0") {
                        Alert::create([
                            'type' => $alertType,
                            'prospect_id' => $prospect->id,
                            'user_id' => $userdata['amid']
                        ]);
                    }
                    if ($userdata['nsm'] != "0") {
                        Alert::create([
                            'type' => $alertType,
                            'prospect_id' => $prospect->id,
                            'user_id' => $userdata['nsmid']
                        ]);
                    }
                }
            }
        }



        $status = $request->input('status');


        if ($data['roles'] == "admin") {
            $thirtyDaysAgo = now()->subDays(30);
            $threeDaysAgo = now()->subDays(3)->toDateString();
            $sevenDaysAgo = now()->subDays(7)->toDateString();
            $fourteenDaysAgo = now()->subDays(14)->toDateString();


            //alert validasi
            $validData3 = Prospect::with("creator", "province")
                ->whereRaw("DATE(created_at) = ?", [$threeDaysAgo])
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->get();

            $validData7 = Prospect::with("creator", "province")
                ->whereRaw("DATE(created_at) = ?", [$sevenDaysAgo])
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->get();
            $validData14 = Prospect::with("creator", "province")
                ->whereRaw("DATE(created_at) = ?", [$fourteenDaysAgo])
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->get();

            if ($validData3->isNotEmpty()) {
                generateAlerts($validData3, 'V3');
            }

            if ($validData7->isNotEmpty()) {
                generateAlerts($validData7, 'V7');
            }
            if ($validData14->isNotEmpty()) {
                generateAlerts($validData14, 'V14');
            }

            //alert review
            $reviewData7 = Prospect::with("creator", "province")
                ->whereRaw("DATE(updated_at) = ?", [$sevenDaysAgo])
                ->where("status", 1)
                ->orderBy("id", 'DESC')
                ->get();
            if ($reviewData7->isNotEmpty()) {
                generateAlerts($reviewData7, 'R7');
            }

            $reviewData14 = Prospect::with("creator", "province")
                ->whereRaw("DATE(updated_at) = ?", [$fourteenDaysAgo])
                ->where("status", 1)
                ->orderBy("id", 'DESC')
                ->get();
            if ($reviewData14->isNotEmpty()) {
                generateAlerts($reviewData14, 'R14');
            }
            $reviewData30 = Prospect::with("creator", "province")
                ->whereRaw("DATE(updated_at) = ?", [$thirtyDaysAgo])
                ->where("status", 1)
                ->orderBy("id", 'DESC')
                ->get();
            if ($reviewData30->isNotEmpty()) {
                generateAlerts($reviewData30, 'R30');
            }

            // Get data where 'created_at' is within the last 30 days
            $adminData = Prospect::with("creator")
                ->where("created_at", "<", $thirtyDaysAgo)
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->update(['status' => 99]);
            //dd($adminData);
            // die();
        }

        $area = $data['area'];

        $url = $request->input('url');
        if ($status == 1) {
            switch ($data['roles']) {
                case "admin":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection", "remarks", "temperature")
                    ->where("status", $status)
                    ->whereHas('temperature', function ($query) {
                        $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                    })
                    ->orderBy('status', 'ASC')
                    ->orderBy("prospects.id", 'DESC')
                    ->get();
                    break;
                case "prj":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection", "remarks", "temperature")
                    ->where("status", $status)
                    ->whereHas('temperature', function ($query) {
                        $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                    })
                    ->where ("is_project",1)
                 
                    ->orderBy('status', 'ASC')
                    ->orderBy("prospects.id", 'DESC')
                    ->get();
                    break;

                case "bu":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                        ->where("status", $status)->where('unit_id', $data['buid'])
                        ->whereHas('temperature', function ($query) {
                            $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                        })
                        ->whereHas('review', function ($query) {
                            $query->where('jenis_anggaran', '<>', 'MABES AD / AL / AU');
                        })
                        ->where ("is_project",0)
                        ->orderBy('status', 'ASC')
                        ->orderBy("id", 'DESC')
                        ->get();
                    break;


                case "fs":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                        ->where("status", $status)->where('pic_user_id', $data['usid'])
                        ->whereHas('temperature', function ($query) {
                            $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                        })
                        ->where ("is_project",0)
                        ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                        ->get();
                    break;

                case "am":
                    $areaArray = explode(',', $area);
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                        ->where("status", $status)
                        ->whereHas('province', function ($query) use ($areaArray) {
                            $query->whereIn('iss_area_code', $areaArray);
                        })
                        ->whereHas('temperature', function ($query) {
                            $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                        })
                        ->where ("is_project",0)
                        ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                        ->get();
                    break;

                case "nsm":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                        ->where("status", $status)
                        ->whereHas('province', function ($query) use ($area) {
                            $query->where('wilayah', $area);
                        })
                        ->whereHas('temperature', function ($query) {
                            $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                        })
                        ->where ("is_project",0)

                        ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                        ->get();
                    break;
            }
        } else {
            switch ($data['roles']) {
                case "admin":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                   
                        ->where("status", '!=', 1)
                
                        ->orderBy('status', 'ASC')
                    ->orderBy("prospects.id", 'DESC')
                    ->get();

                    break;
                case "prj":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                   
                        ->where("status", '!=', 1)
                       ->where ("is_project",1)
                
                        ->orderBy('status', 'ASC')
                    ->orderBy("prospects.id", 'DESC')
                    ->get();

                    break;

     

                case "fs":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    ->whereHas('review', function ($query) {
                        $query->where('jenis_anggaran', '<>', 'MABES AD / AL / AU');
                    })
                    ->where ("is_project",0)
                        ->where("status", '!=', 1)->where('pic_user_id', $data['usid'])->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                        ->get();
                    break;

                case "am":
                    $areaArray = explode(',', $area);
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                        ->where("status", '!=', 1)
                        ->whereHas('province', function ($query) use ($areaArray) {
                            $query->whereIn('iss_area_code', $areaArray);
                        })
                        ->where ("is_project",0)
                        ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                        ->get();
                    break;

                case "nsm":
                    $prv = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                        ->where("status", '!=', 1)
                        ->whereHas('province', function ($query) use ($area) {
                            $query->where('wilayah', $area);
                        })
                        ->where ("is_project",0)
                        ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                        ->get();
                    break;
            }
        }
        //->whereHas('config', function ($query) {
        //  $query->wherePivot('main', 1);
        // })
    

       /* $test = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection", "remarks", "temperature")
        ->join('prospect_temperatures', 'prospect_temperatures.prospect_id', '=', 'prospects.id')
        ->where("status", $status)->whereIn('tempCodeName',[1,2,3,4,5])
        ->orderByRaw("FIELD(prospect_temperatures.tempCodeName, 4, 1,-1, 2, 3, 5,0)")
        ->orderBy('status', 'ASC')
        ->orderBy("prospects.id", 'DESC');
        */
        //dd($test->toSql());



        $dataTable =  DataTables::of($prv)
            ->addIndexColumn()
            ->addColumn('provincedata', function ($prp) {

                $newprov = $prp->province ? $prp->province->name : 'No Province detected';

                $AM = getareaman($prp->province->iss_area_code, $prp->province->wilayah);


                if ($AM['am'] != "0") {
                    $newdata = $newprov . "</br>" . $AM['am'];
                } else 
                if ($AM['nsm'] != "0") {
                    $newdata = $newprov . "</br>" . $AM['nsm'];
                } else
                    $newdata = $newprov . "</br>Tidak ada AM/NSM";
                return $newdata;
            })
            ->addColumn('ProvOrderNo', function ($prp) {

                $newprov = $prp->province ? $prp->province->prov_order_no : 'No Province detected';
                return $newprov;
           
            })
            ->addColumn('AMNSM', function ($prp) {
                $AM = getareaman($prp->province->iss_area_code, $prp->province->wilayah);
                if ($AM['am'] != "0") {
                    $newdata = $AM['am'];
                } else 
            if ($AM['nsm'] != "0") {
                    $newdata = $AM['nsm'];
                } else
                    $newdata = "Tidak ada AM/NSM";
                return $newdata;
            })


            ->addColumn('unit', function ($prp) {

                return $prp->unit ? $prp->unit->name : 'data BU Error';
            })
            ->addColumn('categoryId', function ($prp) {
                return $prp->config->category_id;
            })
            ->addColumn('uom', function ($prp) {
                return $prp->config->uom;
            })
            ->addColumn('namaprod', function ($prp) {
                return $prp->config ? $prp->config->name : 'data config error';
            })
            ->addColumn('configno', function ($prp) {
                return $prp->config->config_code;
            })
            ->addColumn('category', function ($prp) {
                $cats = $prp->config->category_id;
                $cekcat = Category::where('id', $cats)->first();

                return $cekcat ? $cekcat->name : 'data category error';
            })
            ->addColumn('brand', function ($prp) {
                $brand = $prp->config->brand_id;
                $cekbrand = Brand::where('id', $brand)->first();
                //$validname?$validname->name:"Belum di Validasi"
                return $cekbrand ? $cekbrand->name : 'data brand error';
            })

            ->addColumn('price', function ($prp) {

                $rupiah = number_format($prp->config->price_include_ppn, 0, ',-', '.');
                return $rupiah;
            })

            ->addColumn('value', function ($prp) {
                $qty = $prp->qty;
                $price = $prp->config->price_include_ppn;
                $total = $qty * $price;
                $rupiah = number_format($total, 0, ',-', '.');
                return $rupiah;
            })
            ->addColumn('valueraw', function ($prp) {
                $qty = $prp->qty;
                $price = $prp->config->price_include_ppn;
                $total = $qty * $price;

                return $total;
            })
            ->addColumn('etadate', function ($prp) {
                $podate = $prp->eta_po_date;
                $qty = strtotime($podate);
                $now = strtotime(now());

                $diffsec = $qty - $now;
                $diff = floor($diffsec / 86400);

                if ($diff < 0) {
                    return "Tanggal PO sudah Lewat";
                } else return $podate;
            })
            
            ->addColumn('lastupdate', function ($prp) {
             

                $reviewdata=ReviewLog::with('UpdatedBy')->where('review_id',$prp->review->id)->orderBy('updated_at','desc')->orderBy('id','desc')->first();
                $colUpdate = $reviewdata->created_at ?? "Belum Pernah Diupdate Sama Sekali";
                //dd($colUpdate);

                return $colUpdate;
            })

            /*


            ->addColumn('temperaturedata', function ($prp) {
                $ch = $prp->review->chance;
                $chs = number_format($ch * 100, 0);
                $anggaran = $prp->anggaran_status;
                $podate = $prp->eta_po_date;
                $qty = strtotime($podate);
                $now = strtotime(now());

                $diffsec = $qty - $now;
                $diff = floor($diffsec / 86400);
                if ($ch == 0) {
                    return "DROP";
                } else {
                    if ($anggaran == "BELUM ADA" || $anggaran == "USULAN" || $ch==0.2) {
                        return "EARLY STAGE";
                    } else {
                        if ($ch < 0.5 && $ch > 0.2) {
                            return "FUNNEL";
                        } else {
                            if ($diff < 150 && $ch > 0.6) {
                                return "HOT PROSPECT";
                            } else {
                                return "PROSPECT";
                            }
                        }
                    }
                };
            })
            */
            ->addColumn('temperid',function($prp){
                return $prp->temperature->tempCodeName;
            })
            ->addColumn('temperaturebtn', function ($prp) {
                $tempName=$prp->temperature->tempName;
                $tempCode=$prp->temperature->tempCodeName;
                $ch = $prp->review->chance;
                $chs = number_format($ch * 100, 0);
                if($ch <0.4 and $ch >0){
                    $chv="
                    <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>
                    <h5><a href='javascript:void(0)' id='".$prp->id."'  datacode='".$prp->prospect_no."' class='badge tmpe bg-secondary text-light  btn-drop'>Request Drop</a></h5>";
                }elseif($ch>0.7){
                    $chv="
                    <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>
                    <h5><a href='javascript:void(0)' id='".$prp->id."' datacode='".$prp->prospect_no."' class='badge tmpe bg-success text-light  btn-finish'>Set Success</a></h5>";
                }else{
                    $chv="
                    <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>";
                }


                switch ($tempCode){
                    case (-1);
                    return "<h4><span class='badge tmpe bg-secondary text-light'>MISSED</span></h4>".$chv;
                    break;
                    case 0:
                        return "<h4><span class='badge tmpe bg-dark text-light'>DROP</span></h4>".$chv;
                        break;
                    case 1:
                        return "<h4><span class='badge tmpe text-light' style='background-color:CornflowerBlue'>LEAD</span></h4>".$chv;
                        break;
                    case 2:
                        return "<h4><span class='badge tmpe text-light' style='background-color:MediumOrchid'>PROSPECT</span></h4>".$chv;
                        break;
                    case 3:
                        return "<h4><span class='badge tmpe text-light' style='background-color:Salmon'>FUNNEL</span></h4>".$chv;
                        break;
                    case 4:
                        return "<h4><span class='badge tmpe bg-danger text-light'>HOT PROSPECT</span></h4>".$chv;
                        break;
                    case 5:
                        return "<h4><span class='badge tmpe bg-success text-light'>SUCCESS</span></h4>".$ch;
                        break;
                }
                //return $data;
            })


            ->addColumn('promotion', function ($prp) {
                $first = $prp->review->first_offer_date;
                $last = $prp->review->last_offer_date;
                $demo = $prp->review->demo_date;
                $presentation = $prp->review->presentation_date;
                $data = "";
                if (isset($first)) {
                    $data .= "<span class='badge prmo bg-info text-light'>First Offer </br>$first</span>";
                }
                if (isset($demo)) {
                    $data .= "<span class='badge prmo bg-info text-light'>Demo </br>$demo</span>";
                }
                if (isset($presentation)) {
                    $data .= "<span class='badge prmo bg-info text-light'>Presentation </br>$presentation</span>";
                }
                if (isset($last)) {
                    $data .= "<span class='badge prmo bg-info text-light'>Last Offer </br>$last</span>";
                }
                return $data;
            })

            ->addColumn('reviewStats', function ($prp) {
                $usersts = $prp->review->user_status;
                $userc = reviewcolor($usersts);
                $direksi = $prp->review->direksi_status;
                $direksic = reviewcolor($direksi);
                $purcashing = $prp->review->purchasing_status;
                $purcashingc = reviewcolor($purcashing);



                $data = "<span class='badge rvw $userc text-light'>User Status </br>$usersts</span>";
                $data .= "<span class='badge rvw $direksic text-light'>Direksi Status</br>$direksi</span>";
                $data .= "<span class='badge  rvw $purcashingc text-light'>Purchasing Status</br>$purcashing</span>";

                return $data;
            })

            ->addColumn('anggaran', function ($prp) {
                $anggaranstats = $prp->review->anggaran_status;
                $jenisanggaran = $prp->review->jenis_anggaran;


                $data = "<span class='badge angg bg-info text-light'>Anggaran Status</br>$anggaranstats</span>";
                $data .= "<span class='badge angg bg-info text-light'>Jenis Anggaran</br>$jenisanggaran</span>";

                return $data;
            })

            ->addColumn('propdetail', function ($prp) {
                $no = $prp->prospect_no;
                $theroutes = route('admin.prospecteditdata', ['prospect' => $prp->id]);
                

                $data = "<a href=$theroutes onclick='storeSequenceData()'><h5><span class='badge prpno bg-info text-light'>$no</span></h5></a>";
                /*$data.="<a class='nav-link dropdown-toggle' href='#' id='alertsDropdown'role='button'
                data-toggle=' aria-haspopup='true' aria-expanded='>
                <i class='fas fa-bell fa-fw'></i>
                <!-- Counter - Alerts -->
                <span class='badge badge-danger badge-counter' id='remarksCount'>$remarksCount</span>
            </a>";*/
                $data.="</br>$prp->prospect_source";
                
                
                
                return $data;
            })


            ->addColumn('submitdate', function ($prp) {
                $dt = $prp->created_at->format('d-M-Y');
                $creator = $prp->creator->name ? $prp->creator->name :'missing data creator';
            
                $comdata = "(" . $creator . ")</br>" . $dt;
               
                return $comdata;
            })


            ->addColumn('personInCharge', function ($prp) {
                $picdata = "Pilih PIC saat validasi";
                if ($prp->personInCharge !== null) {
                    $picdata = $prp->personInCharge->name;
                }

                return $picdata;
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

            ->addColumn('hospitaltarget',function($prp){
                $target = $prp->hospital->target;
                if($target=="Key Account" ||$target=="Prioritas"){
                    return 1;}
                    else
                {return 0;}
            })
            ->addColumn('city', function ($prp) {
                return $prp->hospital->city;
            })
            ->addColumn('statsname', function ($prp) {
                switch ($prp->status) {


                    case (1):
                        $tampil = "<H4><span class='badge stts bg-success text-light'>VALID</span></H4>";
                        return $tampil;
                        break;
                    case (404):
                        $tampil = "<H4><span class='badge stts bg-danger text-light'>REJECT</span></H4>";
                        return $tampil;
                        break;
                    case (99):
                        $tampil = "<H4><span class='badge stts bg-dark text-light'>EXPIRED</span></H4>";
                        return $tampil;
                        break;

                    case (0):
                        $tampil = "<H4><span class='badge stts bg-info text-light'>NEW</span></H4>";
                        return $tampil;
                }
            })

            ->addColumn('action', function ($prp) use ($url) {

                $editform = route('admin.prospectedit', $prp);
                $validasi = route('admin.prospectvalidation', $prp);
                $util = userutil();
                $roles = ['fs', 'bu', 'admin'];
                $dataRemarks = [];
                if (!empty($prp->remarks)) {
                    $dataRemarks = $prp->remarks;
                }
                $dataRemarksString = !empty($dataRemarks) ? htmlentities(json_encode($dataRemarks)) : '';
                $remarksCount = count($prp->remarks);
                if($remarksCount>0){
                $remarksshowcount='<a href="#" id="remarksPopover" class="btn-remarksPopover" data-remarks="'.$dataRemarksString.'" title="Remarks" >
                      <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" id="remarksCount">'.$remarksCount.'</span>
                </a>';}else $remarksshowcount="";
                //var_dump($dataRemarks);
                $roleToCheck = $util['roles'];
                $test = $url;

                switch ($prp->status) {
                    case (1):
                        if ($url == "prospect") {
                            $theroutes = route('admin.prospecteditdata', (['prospect' => $prp->id]));
                            $btn = "<div class='d-inline-flex align-items-center'><a href='$theroutes' ><h5><span class='badge bg-primary text-light'>Detail Data</span></h5></a></div>";
                            if (in_array($roleToCheck, $roles)) {
                                $btn .= '<div class="d-inline-flex align-items-center"><a href="javascript:void(0)" class="btn-remarks" data-id="' . $prp->id . '" ><h5><span class="badge bg-warning text-light">Remarks</span></h5></a>';
                                $btn.=$remarksshowcount.'</div>';
                            }
                        } else {
                            $btn = '<div class="row"><a href="' . $editform . '" class="btn aksi  btn-primary btn-sm ml-2 btn-edit">Edit</a> ';
                            //$btn .= '<a href="javascript:void(0)" id="'.$prp->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
                        }

                        return $btn;
                        break;

                    case (99):
                        $btn = '<div class="row"><a href="javascript:void(0)" id="' . $prp->id . '"" class="btn btnaksi btn-warning aksi btn-sm ml-2 btn-renew">Renew</a>';
                        return $btn;
                        break;
                    case (404):
                        $btn = "Reject Karena " . $prp->rejection->reason;
                        return $btn;
                        break;
                    default:

                        $btn = '<div class="row"><a href="javascript:void(0)" id="' . $prp->id . '" class="btn aksi btnaksi btn-warning btn-sm ml-2 btn-validasi">Approval</a></div>';
                        $btn .= '<div class="row mt-1 mr-1"><a href="javascript:void(0)" id="' . $prp->id . '" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-edit">Edit</a></div>';

                        return $btn;
                }
            })

            ->addColumn('validasi', function ($prp) {

                $startdate = null;
                $formattedDate = '';
                $data = 'Belum di Validasi';

                if ($prp->validation_time !== null) {
                    $startdate = new \DateTime($prp->validation_time); // Create a DateTime object
                    $formattedDate = $startdate->format('Y-m-d'); // Format to get only the date part
                }

                if ($prp->validation_by !== null) {
                    $validname = User::where('id', $prp->validation_by)->first();
                    $data = $validname ? $validname->name : "Belum di Validasi";
                }

                $show = $data . '</br>' . $formattedDate;

                return $show;
            })

         

            //->only(['id','creator','personInCharge',"prospect_no"])
            ->rawColumns(['propdetail', 'action', 'promotion', "reviewStats", 'temperaturebtn', 'statsname', "submitdate", "provincedata", "anggaran", 'hospitaldata', 'validasi'])

            ->toJson([]);

        $utils = userutil();
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



    
    public function ConsumablesProspectData(Request $request)
    {
        function reviewcolorCons($isi)
        {
            switch ($isi) {
                case ("POSITIF"):
                    return "bg-success";
                    break;
                case ("NEUTRAL"):
                    return "bg-warning";
                    break;
                case ("SETUJU"):
                    return "bg-primary";
                    break;
                default:
                    return "bg-danger";
            }
        }

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

        function generateAlertsCons($prospects, $alertType)
        {
            foreach ($prospects as $prospect) {
                $userdata = getam($prospect->province->iss_area_code, $prospect->province->wilayah);

                $existingAlert = Alert::where('prospect_id', $prospect->id)->where('type', $alertType)->first();

                // Create alert if an appropriate alert type is generated
                if (!$existingAlert) {
                    if ($userdata['am'] != "0") {
                        Alert::create([
                            'type' => $alertType,
                            'prospect_id' => $prospect->id,
                            'user_id' => $userdata['amid']
                        ]);
                    }
                    if ($userdata['nsm'] != "0") {
                        Alert::create([
                            'type' => $alertType,
                            'prospect_id' => $prospect->id,
                            'user_id' => $userdata['nsmid']
                        ]);
                    }
                }
            }
        }



        $status = $request->input('status');


        if ($data['roles'] == "admin") {
            $thirtyDaysAgo = now()->subDays(30);
            $threeDaysAgo = now()->subDays(3)->toDateString();
            $sevenDaysAgo = now()->subDays(7)->toDateString();
            $fourteenDaysAgo = now()->subDays(14)->toDateString();


            //alert validasi
            $validData3 = ConsumablesProspect::with("creator", "province")
                ->whereRaw("DATE(created_at) = ?", [$threeDaysAgo])
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->get();

            $validData7 = ConsumablesProspect::with("creator", "province")
                ->whereRaw("DATE(created_at) = ?", [$sevenDaysAgo])
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->get();
            $validData14 = ConsumablesProspect::with("creator", "province")
                ->whereRaw("DATE(created_at) = ?", [$fourteenDaysAgo])
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->get();

            if ($validData3->isNotEmpty()) {
                generateAlertsCons($validData3, 'CV3');
            }

            if ($validData7->isNotEmpty()) {
                generateAlertsCons($validData7, 'CV7');
            }
            if ($validData14->isNotEmpty()) {
                generateAlertsCons($validData14, 'CV14');
            }

            //alert review
            $reviewData7 = ConsumablesProspect::with("creator", "province")
                ->whereRaw("DATE(updated_at) = ?", [$sevenDaysAgo])
                ->where("status", 1)
                ->orderBy("id", 'DESC')
                ->get();
            if ($reviewData7->isNotEmpty()) {
                generateAlertsCons($reviewData7, 'CR7');
            }

            $reviewData14 = ConsumablesProspect::with("creator", "province")
                ->whereRaw("DATE(updated_at) = ?", [$fourteenDaysAgo])
                ->where("status", 1)
                ->orderBy("id", 'DESC')
                ->get();
            if ($reviewData14->isNotEmpty()) {
                generateAlertsCons($reviewData14, 'CR14');
            }
            $reviewData30 = ConsumablesProspect::with("creator", "province")
                ->whereRaw("DATE(updated_at) = ?", [$thirtyDaysAgo])
                ->where("status", 1)
                ->orderBy("id", 'DESC')
                ->get();
            if ($reviewData30->isNotEmpty()) {
                generateAlertsCons($reviewData30, 'CR30');
            }

            // Get data where 'created_at' is within the last 30 days
            $adminData = ConsumablesProspect::with("creator")
                ->where("created_at", "<", $thirtyDaysAgo)
                ->where("status", 0)
                ->orderBy("id", 'DESC')
                ->update(['status' => 99]);
            //dd($adminData);
            // die();
        }

        $area = $data['area'];

        $url = $request->input('url');
        if ($status != 0) {
            switch ($data['roles']) {
                case "admin":
                    $prv = ConsumablesProspect::with("creator", "personInCharge", "hospital", "province", "department", "unit")
                    ->where("status","<>", 0)
                  
                    ->get();
                    break;

                case "bu":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", $status)->where('unit_id', $data['buid'])
                    //     ->whereHas('temperature', function ($query) {
                    //         $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                    //     })
                    //     ->orderBy('status', 'ASC')
                    //     ->orderBy("id", 'DESC')
                    //     ->get();
                    break;


                case "fs":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", $status)->where('pic_user_id', $data['usid'])
                    //     ->whereHas('temperature', function ($query) {
                    //         $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                    //     })
                    //     ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                    //     ->get();
                    break;

                case "am":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", $status)
                    //     ->whereHas('province', function ($query) use ($area) {
                    //         $query->where('iss_area_code', $area);
                    //     })
                    //     ->whereHas('temperature', function ($query) {
                    //         $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                    //     })
                    //     ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                    //     ->get();
                    break;

                case "nsm":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", $status)
                    //     ->whereHas('province', function ($query) use ($area) {
                    //         $query->where('wilayah', $area);
                    //     })
                    //     ->whereHas('temperature', function ($query) {
                    //         $query->where('tempCodeName', '<>', 0)->Where('tempCodeName', '<>', 5);
                    //     })

                    //     ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                    //     ->get();
                    break;
            }
        } else {
            switch ($data['roles']) {
                case "admin":
                    $prv = ConsumablesProspect::with("creator","category", "personInCharge", "hospital", "province", "department", "unit")
                   
                        ->where("status", '<', 1)
                        ->groupBy("tempCode")
                   
                    ->get();

                    break;

                case "fs":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", '!=', 1)->where('pic_user_id', $data['usid'])->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                    //     ->get();
                    break;

                case "am":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", '!=', 1)
                    //     ->whereHas('province', function ($query) use ($area) {
                    //         $query->where('iss_area_code', $area);
                    //     })
                    //     ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                    //     ->get();
                    break;

                case "nsm":
                    // $prv = ConsumablesProspect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection","remarks","temperature")
                    //     ->where("status", '!=', 1)
                    //     ->whereHas('province', function ($query) use ($area) {
                    //         $query->where('wilayah', $area);
                    //     })
                    //     ->orderBy('status', 'ASC')->orderBy("id", 'DESC')
                    //     ->get();
                    break;
            }
        }
        //->whereHas('config', function ($query) {
        //  $query->wherePivot('main', 1);
        // })
    

       /* $test = Prospect::with("creator", "hospital", "review", "province", "department", "unit", "config", "rejection", "remarks", "temperature")
        ->join('prospect_temperatures', 'prospect_temperatures.prospect_id', '=', 'prospects.id')
        ->where("status", $status)->whereIn('tempCodeName',[1,2,3,4,5])
        ->orderByRaw("FIELD(prospect_temperatures.tempCodeName, 4, 1,-1, 2, 3, 5,0)")
        ->orderBy('status', 'ASC')
        ->orderBy("prospects.id", 'DESC');
        */
        //dd($test->toSql());



        $dataTable =  DataTables::of($prv)
            ->addIndexColumn()
            ->addColumn('creatorname', function ($prp) {
                $dt = $prp->created_at->format('d-M-Y');
                $creator = $prp->creator->name ? $prp->creator->name :'missing data creator';
            
                $comdata = "<i>" . $creator . "</i></br>" . $dt;
               
                return $comdata;
            })
            ->addColumn('personInCharge', function ($prp) {
                $picdata = "Pilih PIC saat validasi";
                if ($prp->personInCharge !== null) {
                    $picdata = $prp->personInCharge->name;
                }

                return $picdata;
            })
            ->addColumn('catdata', function ($prp) {
                
                $catdata = $prp->category->name;

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
            ->addColumn("review", function($prp) {
                $data = $prp->id;
                return  "Test REVIEW" ;
            })
            ->addColumn("anggaran", function($prp) {
                
                return  "TEST ANGGARAN" ;
            })
            ->addColumn("po_target", function($prp) {
                
                $data = $prp->status;
                switch($data){
                    case 1:
                        return "<h5><span class='badge badge-info'>".$prp->po_target."</span></h5>";
                        break;
                    case 2:
                        return "<h5><span class='badge badge-warning'>".$prp->po_target."</span></h5>";
                        break;
                    case 3:
                        return "<h5><span class='badge badge-info'>".$prp->po_target."</span></h5>";
                        break;
                    case 4:
                        return "<h5><span class='badge badge-secondary'>".$prp->po_target."</span></h5>";
                        break;
                    case -1:
                        return "<h5><span class='badge badge-secondary'>".$prp->po_target."</span></h5>";
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
            ->addColumn('etadate', function ($prp) {
                
                $podate = $prp->eta_po_date;
                if($podate){
                $qty = strtotime($podate);
                $now = strtotime(now());

                $diffsec = $qty - $now;
                $diff = floor($diffsec / 86400);

                if ($diff < 0) {
                    return "Tanggal Estimasi PO sudah Lewat";
                } else return $podate;
                }
                else

                return "Tanggal Estimasi PO Belum Ada";
            })


            ->addColumn("status", function($prp) {
                $data = $prp->status;

                switch($data){
                    case -1 :
                        return "<h5><span class='badge badge-secondary'>MISSED</span></h5>";
                        break;
                    case 0 :
                        return "<h5><span class='badge badge-info'>WAIT APPROVAL</span></h5>";
                        break;
                    case 1 :
                        return "<h5><span class='badge badge-primary'>NEW</span></h5>";
                        break;
                    case 2 :
                        return "<h5><span class='badge badge-warning'>CONSIGN</span></h5>";
                        break;
                    case 3 :
                        return "<h5><span class='badge badge-info'>READY STOCK</span></h5>";
                        break;
                    case 4 :
                        return "<h5><span class='badge badge-secondary'>WAIT STOCK / ORDERING STOCK</span></h5>";
                        break;
                    case 404 :
                        return "<h5><span class='badge badge-danger'>REJECT</span></h5>";
                        break;
                    case 99 :
                        return "<h5><span class='badge badge-dark'>EXPIRED</span></h5>";
                        break;
                    case 77 :
                        return "<h5><span class='badge badge-succes'>DONE / SUCCESS</span></h5>";
                        break;
                 }


                
            })

            ->addColumn('action', function ($prp) use ($url) {
                $routesdetail ="#";
                $theroutes = route('buyerforecast', ['deptVendorList' => $prp->id]);
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




            //->only(['id','creator','personInCharge',"prospect_no"])
            ->rawColumns(["cnpropdetail","action","creatorname","provincedata","catdata","hospitaldata","Items","po_target","status"])

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

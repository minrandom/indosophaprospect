<?php

namespace App\Http\Controllers;

use App\Models\successReq;
use App\Models\Department;
use App\Models\Config;

use App\Models\User;
use App\Models\Unit;
use App\Models\Hospital;
use App\Models\Province;
use App\Models\prospectTemperature;
use App\Models\Review;
use DataTables;
use Illuminate\Http\Request;

class SuccessReqController extends Controller
{
   
    public function index()
    {
        //
        return view('admin.successrequest');
    }

    public function data()
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

        $prv = successReq::with('prospect','validation','request','prospect.province','prospect.hospital','prospect.unit');

        $prv = $prv->get();

        $datatables = DataTables::of($prv)
            ->addColumn('action', function ($hpital) {
                $btn = '<div class="row"><a href="javascript:void(0)" id="' . $hpital->id . '" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                //$btn .= '<a href="javascript:void(0)" id="' . $hpital->id . '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
                return $btn;
            })
            
            ->addColumn('hospital',function($drop){
                $data = Hospital::with('province')->where('id',$drop->prospect->hospital_id)->first();
                $show = $data->name.'</br>'.$data->province->name;
                return $show;
            })
            ->addColumn('bunit',function($drop){
                $data = Unit::where('id',$drop->prospect->unit_id)->first();
                $show = $data->name;
                return $show;
            })
            ->addColumn('regioncode',function($drop){
                $data = Province::where('id',$drop->prospect->province_id)->first();
                $show = $data->prov_region_code;
                return $show;
            })
            ->addColumn('issareacode',function($drop){
                $data = Province::where('id',$drop->prospect->province_id)->first();
                $show = $data->iss_area_code;
                return $show;
            })
            ->addColumn('wilayah',function($drop){
                $data = Province::where('id',$drop->prospect->province_id)->first();
                $show = $data->wilayah;
                return $show;
            })
            ->addColumn('requestData',function($drop){
                $data = User::with('employee')->where('id',$drop->request_by)->first();
                $show = $data->name.'</br>'.$drop->request_date;
                return $show;
            })
            ->addColumn('validator',function($drop){
                if($drop->validation_by){
                $data = User::with('employee')->where('id',$drop->validation_by)->first();
                $show = $data->name.'</br>'.$drop->validation_time;
                return $show;
                }else{
                    return "Belum di Approve";
                }
            })
          
            ->addColumn('bunoted',function($drop){
                if($drop->isBuNoted < 1){
                $show = "TIM BU Belum Mengetahui";
                return $show;
                }else{
                    return "Tim BU Sudah Mengetahui </br>".$drop->bu_noted_at;
                }
            })
            ->addColumn('statusreq',function($drop){
                if($drop->validation_status < 1){
                $show = '<div class="row"><a href="javascript:void(0)" id="'.$drop->id.'" class="btn aksi btnaksi btn-warning btn-sm ml-2 btn-validasi">REQUEST</a></div>';
                return $show;
                }else{
                    return '<div class="row"><a href="javascript:void(0)" id="'.$drop->id.'" class="btn aksi btnaksi btn-info btn-sm ml-2">RECORDED</a></div>';
                }
            })
            ->addColumn('requestReason',function($drop){
                $data ="";
                switch ($drop->request_reason){
                    case 1: 
                        $data = 'Price and Budget';
                    break;
                    case 2:
                        $data ='Quality';
                    break;
                    case 3:
                        $data ='Doctor Choice';
                    break;
                    case 4:
                        $data ='Management Trust';
                    break;
                    case 5:
                        $data ='Key Person';
                    break;
                    case 6:
                        $data ='Good Data';
                    break;
                    default: $data = "NO REASON DATA";
                }

                $show = $data .'</br></br>'.$drop->keterangan;
                return $show;
            })
            ->addColumn('dept',function($drop){
                $data = Department::where('id',$drop->prospect->department_id)->first();
                $show = $data->name;
                return $show;
            })
            ->addColumn('config',function($drop){
                $data = Config::where('id',$drop->prospect->config_id)->first();
                $show = $data->name;
                return $show;
            })
            ->addColumn('prospect_no', function ($drop) {
                $btn = '<div class="row"><a href="'.route('admin.prospecteditdata', $drop->prospect->id).'" class="btn btn-primary btn-sm ml-2 btn-edit">'.$drop->prospect->prospect_no.'</a></div>';
                return $btn;
            })
            ->rawColumns(["action","hospital",'requestData','requestReason','validator','bunoted','statusreq','prospect_no'])

            ->toJson([]);

            $utils = userutilCons();
            $data = $datatables->original;
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

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        successReq::create([
            "prospect_id"=>$request->idprospect,
            "request_by"=>$request->creatorid,
            "request_date"=>$request->createddate,
            "request_reason"=>$request->cr8reason,
            "keterangan"=>$request->cr8messages,     
        ]);
        return response()->json(['message' => 'Success Request']);

    }

    public function show(successReq $successReq)
    {
        //

        $data=$successReq;
        $config = Config::where('id',$successReq->prospect->config_id)->first();
         $data['config']=$config->name;
        
         $requester = User::with('employee')->where('id',$successReq->request_by)->first();
        $data['request']= $requester->name;
 
        $hos = Hospital::with('province')->where('id',$successReq->prospect->hospital_id)->first();
         $hos->name;
         $data['hospital']=$hos->name;
 
         switch ($successReq->request_reason){
             case 1: 
                 $data['reason'] = 'Price and Budget';
             break;
             case 2:
                 $data['reason'] ='Quality';
             break;
             case 3:
                 $data['reason'] ='Doctor Choice';
             break;
             case 4:
                 $data['reason'] ='Management Trust';
             break;
             case 5:
                 $data['reason'] ='Key Person';
             break;
             case 6:
                 $data['reason'] ='Good Data';
             break;
             default: $data['reason'] = "NO REASON DATA";
         }
 
 
 
         return response()->json(['message' => 'Success Request','data'=>$data]);
 
 
    }

    public function edit(successReq $successReq)
    {
        //
    }


    public function update(Request $request, successReq $successReq)
    {
        //

        $successReq->update([
            "validation_status"=>$request->updateTo,
            "validation_time"=>now()->format('Y-m-d'),
            "validation_by"=>$request->creatorid
        ]);

        $prospectrev = Review::where('prospect_id',$successReq->prospect_id)->first();

        $prospectrev->update([
            'chance'=>1,
        ]);

        $temp = prospectTemperature::where('prospect_id',$successReq->prospect_id)->first();
        $temp->update(['tempName'=>"SUCCESS",'tempCodeName'=>5]);


    }


    public function destroy(successReq $successReq)
    {
        //
    }


}

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
use App\Models\Prospect;
use App\Models\Alert;
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
        
        
        ->addColumn('action', function($employee){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$employee->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$employee->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

             return $btn;
     })
  ->addColumn('action2', function($employee){
            
            $btn2= $employee->user->role;
            $btn2 .= ' <div class="row"><a href="javascript:void(0)" id="'.$employee->user->id.'" class="btn btn-primary btn-sm ml-2 btn-editrole">Edit Role</a>';
            $btn2 .= ' <a href="javascript:void(0)" id="'.$employee->user->id.'" class="btn btn-danger btn-sm ml-2 btn-clear">Set To User</a></div>';
            $btnx= new HtmlString($btn2);
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


public function CreatedData(){

    $user = auth()->user();
    
    $usrole=$user->role;
  
    $usid=$user->id;
    
    $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
    ->where("status",'!=',1)
    ->where("user_creator",$usid)
    ->orderBy('status','ASC')
    ->orderBy("id",'DESC')
    ->get();

    return DataTables::of($prv)
    ->addIndexColumn()  
    ->addColumn('submitdate', function ($prp) {
        $dt=$prp->created_at->format('d-M-Y');
        return $dt;
        })
    ->addColumn('hospital', function ($prp) {
        $dep=$prp->department->name;
        $host=$prp->hospital->name;
        $muncul=$host."</br>".$dep;
        return $muncul;
    })
    ->addColumn('price', function ($prp) {
        
        $rupiah =number_format($prp->config->price_include_ppn,0,',-','.');
        return $rupiah ;
    }) 
    ->addColumn('statsname', function ($prp) {
        switch ($prp->status){
           
            
           case(1):
               $tampil = "<H4><span class='badge stts bg-success text-light'>VALID</span></H4>";
               return $tampil;
               break;
               case(404):
                   $tampil = "<H4><span class='badge stts bg-danger text-light'>REJECT</span></H4>";
                   return $tampil;
               break;
               case(99):
                   $tampil = "<H4><span class='badge stts bg-dark text-light'>EXPIRED</span></H4>";
               return $tampil;
               break;
   
               case(0):
               $tampil = "<H4><span class='badge stts bg-info text-light'>NEW</span></H4>";
               return $tampil;
   
               
           }
   
           })
           ->addColumn('action', function($prp){
        
            $editform=route('admin.prospectedit',$prp);
            $validasi=route('admin.prospectvalidation',$prp);
           
            switch($prp->status){
                case(0):
                    $btn = '<div class="row"><a xid(0)" id="'.$prp->id.'" class="btn btnaksi btn-info aksi btn-sm ml-2 btn-reminder">Reminder</a>';
                    return $btn;
                    break; 
                case(99):
                $btn = '<div class="row"><a xid(0)" id="'.$prp->id.'" class="btn btnaksi btn-warning aksi btn-sm ml-2 btn-renew">Renew</a>';
                return $btn;
                break;
            case(404):
                $btn = "Reject Karena ".$prp->rejection->reason;
                return $btn;
                break;
                default:
            
            $btn="";
            if(auth()->user()->role!='fs'){
            $btn .= '<div class="row"><a href="javascript:void(0)" id="'.$prp->id.'" class="btn aksi btnaksi btn-warning btn-sm ml-2 btn-validasi">Approval</a></div>';
            }
            
            $btn .= '<div class="row mt-1 mr-1"><a href="javascript:void(0)" id="'.$prp->id.'" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-edit">Edit</a></div>';
            
            return $btn;
    
            }
             
            })
    ->rawColumns(['hospital','statsname',"action"])
        
    ->toJson();



}


public function getProductDetail(Request $request)
{
    
    $detailselected=Config::where('name',$request->product);
    return response()->json($detailselected);
}



 public function HospitalData()
 {

    $prv = Hospital::with('province')->get();

    return DataTables::of($prv)
        
        
        ->addColumn('action', function($hpital){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$hpital->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$hpital->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

             return $btn;
          })
      
     ->addColumn('provname', function ($hpital) {
        return $hpital->province->name;
    })
    ->addColumn('prov_orderno', function ($hpital) {
        return $hpital->province->prov_order_no;
    })
    ->only(['id','code','name','action','category','city','address','ownership','owned_by','class','akreditas','target', 'provname', 'prov_orderno'])
    ->rawColumns(['action'])  
        ->toJson();

 }

 public function ConfigData()
 {

    $cnf = Config::with('unit','brand')->get();

    return DataTables::of($cnf)
        
        
        ->addColumn('action', function($cfg){
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$cfg->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
            $btn .= '<a href="javascript:void(0)" id="'.$cfg->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

             return $btn;
          })
      
     ->addColumn('bu', function ($cfg) {
        return $cfg->unit->name;
    })
    ->addColumn('brand', function ($cfg) {
        return $cfg->brand->name;
    })
    ->addColumn('brandcountry', function ($cfg) {
        return $cfg->brand->country;
    })
    ->addColumn('pformat', function ($cfg) {
        $pformat=number_format($cfg->price_include_ppn);
        return $pformat;
    })
    //->only(['id','code','name','action','category','city','address','ownership','owned_by','class','akreditas','target', 'provname', 'prov_orderno'])
    ->rawColumns(['action'])  
        ->toJson();

 }





 public function ProspectData(Request $request)
 {
     function reviewcolor($isi)
    {
        switch($isi)
        {
            case("POSITIF"):
                return "bg-success"; 
                break;
            case("NEUTRAL"):
                return "bg-warning";
                break;
            case("SETUJU"):
                return "bg-primary";
                break;
            default: return "bg-danger";
        }

    }
    $user = auth()->user();
    $usid=$user->id;
    $roles= $user->role;
    $imnow=User::with('employee')->where('id',$usid)->first();
    $area=$imnow->employee->area;


    function getareaman($isi,$isi2)
    {
        $am=Employee::with('user')->where([['area',$isi],['position','AM']])->get();
        $nsm=Employee::with('user')->where([['area',$isi2],['position','NSM']])->get();
        $result = [];

        if ($am->count() > 0) {
            $useram = $am->pluck('user.name');
            $useramid = $am->pluck('user.id');
            $arr1=json_decode($useramid);
            $array = json_decode($useram);
            $result['amid']=$arr1[0];
            $result['am'] = $array[0];
        } else {
            $result['am'] = "0";
        }
    
        if ($nsm->count() > 0) {
            $usernsm = $nsm->pluck('user.name');
            $usernsmid = $nsm->pluck('user.id');
            $arr1=json_decode($usernsmid);
            $array = json_decode($usernsm);
            $result['nsmid']=$arr1[0];
            $result['nsm'] = $array[0];
        } else {
            $result['nsm'] = "0";
        }
    
        return $result;
    };

    function generateAlerts($prospects,$alertType)
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



    $status= $request->input('status');

    if($roles =="admin"){
        $thirtyDaysAgo = now()->subDays(30);
        $threeDaysAgo = now()->subDays(3)->toDateString();
        $sevenDaysAgo = now()->subDays(7)->toDateString();
        $fourteenDaysAgo = now()->subDays(14)->toDateString();
       

        //alert validasi
        $validData3 = Prospect::with("creator","province")
        ->whereRaw("DATE(created_at) = ?", [$threeDaysAgo])
            ->where("status",0)
            ->orderBy("id", 'DESC')
            ->get();

        $validData7 = Prospect::with("creator","province")
        ->whereRaw("DATE(created_at) = ?", [$sevenDaysAgo])
            ->where("status",0)
            ->orderBy("id", 'DESC')
            ->get();
        $validData14 = Prospect::with("creator","province")
        ->whereRaw("DATE(created_at) = ?", [$fourteenDaysAgo])
            ->where("status",0)
            ->orderBy("id", 'DESC')
            ->get();
        
        if($validData3->isNotEmpty()){
            generateAlerts($validData3,'V3');
        }

        if($validData7->isNotEmpty()){
            generateAlerts($validData7,'V7');
        }
        if($validData14->isNotEmpty()){
            generateAlerts($validData14,'V14');
        }

        //alert review
        $reviewData7 = Prospect::with("creator","province")
        ->whereRaw("DATE(updated_at) = ?", [$sevenDaysAgo])
            ->where("status",1)
            ->orderBy("id", 'DESC')
            ->get();
        if($reviewData7->isNotEmpty()){
                generateAlerts($reviewData7,'R7');
        }

        $reviewData14 = Prospect::with("creator","province")
        ->whereRaw("DATE(updated_at) = ?", [$fourteenDaysAgo])
            ->where("status",1)
            ->orderBy("id", 'DESC')
            ->get();
        if($reviewData14->isNotEmpty()){
                generateAlerts($reviewData14,'R14');
        }
        $reviewData30 = Prospect::with("creator","province")
        ->whereRaw("DATE(updated_at) = ?", [$thirtyDaysAgo])
            ->where("status",1)
            ->orderBy("id", 'DESC')
            ->get();
        if($reviewData30->isNotEmpty()){
                generateAlerts($reviewData30,'R30');
        }

        // Get data where 'created_at' is within the last 30 days
        $adminData = Prospect::with("creator")
            ->where("created_at", "<", $thirtyDaysAgo)
            ->where("status",0)
            ->orderBy("id", 'DESC')
            ->update(['status'=>99]);
        //dd($adminData);
       // die();
    }



    $url=$request->input('url');
    if($status==1){
        switch ($roles) {
            case "admin":
                $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
                ->where("status",$status)->orderBy('status','ASC')->orderBy("id",'DESC') 
               ->get();
              
               break;

            case "fs":
                $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
                ->where("status",$status)->where('pic_user_id',$usid)->orderBy('status','ASC')->orderBy("id",'DESC') 
               ->get();


                break;

            case "am":
                $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
                ->where("status",$status)
                ->whereHas('province', function ($query) use ($area) {
                        $query->where('iss_area_code', $area);
                    })
                ->orderBy('status','ASC')->orderBy("id",'DESC') 
               ->get();
               break;
            
            case "nsm":
                $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
                ->where("status",$status)
                ->whereHas('province', function ($query) use ($area) {
                        $query->where('wilayah', $area);
                    })
                ->orderBy('status','ASC')->orderBy("id",'DESC') 
               ->get();


                break;
            }
    }
    else{
    switch ($roles) {
        case "admin":
            $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
                ->where("status",'!=',1)->orderBy('status','ASC')
                ->orderBy("id",'DESC')
                ->get();
            
        
        break;
        
        case "fs":
            $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
            ->where("status",'!=',1)->where('pic_user_id',$usid)->orderBy('status','ASC')->orderBy("id",'DESC') 
           ->get();


            break;

        case "am":
            $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
            ->where("status",'!=',1)
            ->whereHas('province', function ($query) use ($area) {
                    $query->where('iss_area_code', $area);
                })
            ->orderBy('status','ASC')->orderBy("id",'DESC') 
           ->get();
        
           break;

        case "nsm":
            $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
            ->where("status",'!=',1)
            ->whereHas('province', function ($query) use ($area) {
                    $query->where('wilayah', $area);
                })
            ->orderBy('status','ASC')->orderBy("id",'DESC') 
           ->get();


            break;



        }
    }
    //->whereHas('config', function ($query) {
      //  $query->wherePivot('main', 1);
   // })
   

    return DataTables::of($prv)
    ->addIndexColumn()   
    ->addColumn('province', function ($prp) {

    $newprov=$prp->province->name."(".$prp->province->prov_order_no.")";
    $AM= getareaman($prp->province->iss_area_code,$prp->province->wilayah);
    
    
    if($AM['am']!="0"){ 
    $newdata=$newprov."</br>".$AM['am'];} else 
    if($AM['nsm']!="0"){
    $newdata=$newprov."</br>".$AM['nsm'];} else
    $newdata=$newprov."</br>Tidak ada AM/NSM";
    return $newdata;

    })
    
    
    ->addColumn('unit', function ($prp) {
        return $prp->unit->name;
    }) 
    ->addColumn('category', function ($prp) {
        return $prp->config->category;
    }) 
    ->addColumn('namaprod', function ($prp) {
        return $prp->config->name;
    }) 
    ->addColumn('configno', function ($prp) {
        return $prp->config->config_code;
    }) 
    ->addColumn('price', function ($prp) {
        
        $rupiah =number_format($prp->config->price_include_ppn,0,',-','.');
        return $rupiah ;
    }) 
    
    ->addColumn('value', function ($prp) {
       $qty=$prp->qty;
       $price=$prp->config->price_include_ppn;
       $total=$qty*$price;
       $rupiah =number_format($total,0,',-','.');
       return $rupiah;
    })
    ->addColumn('etadate', function ($prp) {
        $podate=$prp->eta_po_date;
        $qty=strtotime($podate);
       $now=strtotime(now());
       
       $diffsec= $qty-$now;
        $diff=floor($diffsec/86400);
        
        if($diff<0){
            return "Tanggal PO sudah Lewat";
        }
        else return $podate;
        
        
        
       
    })

    
    

    ->addColumn('temperature', function ($prp) {
        $ch= $prp->review->chance;
        $chs=number_format($ch * 100, 0);
        $anggaran = $prp->anggaran_status;
        $podate=$prp->eta_po_date;
        $qty=strtotime($podate);
        $now=strtotime(now());
        
        $diffsec= $qty-$now;
        $diff=floor($diffsec/86400);

        if($ch==0){
            return "<h4><span class='badge tmpe bg-dark text-light'>DROP</span></h4>
            <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>";
        }else
        if($anggaran == "BELUM ADA"||$anggaran=="USULAN"){
            return "<h4><span class='badge tmpe bg-secondary text-light'>PROSPECT</span></h4>
            <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>";
        }else
        if($ch<0.5){
            return "<h4><span class='badge tmpe bg-warning text-light'>FUNNEL</span></h4>
            <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>";
        }else 
        if ($diff<150){
            return "<h4><span class='badge tmpe bg-danger text-light'>HOT PROSPECT</span></h4>
            <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>";
        }
        else return "<h4><span class='badge tmpe bg-warning text-light'>FUNNEL</span></h4>
        <h5><span class='badge tmpe bg-info text-light'>Chance </br>$chs %</span></h5>";
        

        
        
      //return $data;
     })


    ->addColumn('promotion', function ($prp) {
        $first= $prp->review->first_offer_date;
        $last= $prp->review->last_offer_date;
        $demo= $prp->review->demo_date;
        $presentation= $prp->review->presentation_date;
       $data ="";
       if(isset($first)){
        $data.= "<span class='badge prmo bg-info text-light'>First Offer </br>$first</span>"; 
       }
       if(isset($demo)){
        $data.="<span class='badge prmo bg-info text-light'>Demo </br>$demo</span>";
       }
       if(isset($presentation)){
        $data.="<span class='badge prmo bg-info text-light'>Presentation </br>$presentation</span>";
       }
       if(isset($last)){
           $data.="<span class='badge prmo bg-info text-light'>Last Offer </br>$last</span>";
        }
        return $data;
        })
    ->addColumn('review', function ($prp) {
        $user= $prp->review->user_status;
        $userc=reviewcolor($user);
        $direksi= $prp->review->direksi_status;
        $direksic= reviewcolor($direksi);
        $purcashing= $prp->review->purchasing_status;
        $purcashingc= reviewcolor($purcashing);


        
        $data= "<span class='badge rvw $userc text-light'>User Status </br>$user</span>"; 
        $data.= "<span class='badge rvw $direksic text-light'>Direksi Status</br>$direksi</span>"; 
        $data.= "<span class='badge  rvw $purcashingc text-light'>Purchasing Status</br>$purcashing</span>"; 
        
        return $data;
        })

    ->addColumn('anggaran', function ($prp) {
        $anggaranstats = $prp->review->anggaran_status;
        $jenisanggaran = $prp->review->jenis_anggaran;

        
        $data= "<span class='badge angg bg-info text-light'>Anggaran Status</br>$anggaranstats</span>"; 
        $data.= "<span class='badge angg bg-info text-light'>Jenis Anggaran</br>$jenisanggaran</span>"; 
         
        return $data;
     })

     ->addColumn('propdetail', function ($prp) {
       $no=$prp->prospect_no;
       $theroutes=route('admin.prospecteditdata',['prospect'=>$prp->id]);
       $data= "<a href='$theroutes' ><h5><span class='badge prpno bg-info text-light'>$no</span></h5></a>";  
        return $data;
     })




        ->addColumn('submitdate', function ($prp) {
            $dt=$prp->created_at->format('d-M-Y');
            $creator=$prp->creator->name;
            $comdata="(".$creator.")</br>".$dt;
            return $comdata;
            })
       
      
        ->addColumn('personInCharge', function ($prp) {
            return $prp->personInCharge ? $prp->personInCharge->name : "Pilih PIC saat validasi";
        })

 


  ->addColumn('hospital', function ($prp) {
    $dep=$prp->department->name;
    $host=$prp->hospital->name;
    $muncul=$host."</br>".$dep;
     return $muncul;
    })
    ->addColumn('city', function ($prp) {
    return $prp->hospital->city;
        })
    ->addColumn('statsname', function ($prp) {
     switch ($prp->status){
        
         
        case(1):
            $tampil = "<H4><span class='badge stts bg-success text-light'>VALID</span></H4>";
            return $tampil;
            break;
            case(404):
                $tampil = "<H4><span class='badge stts bg-danger text-light'>REJECT</span></H4>";
                return $tampil;
            break;
            case(99):
                $tampil = "<H4><span class='badge stts bg-dark text-light'>EXPIRED</span></H4>";
            return $tampil;
            break;

            case(0):
            $tampil = "<H4><span class='badge stts bg-info text-light'>NEW</span></H4>";
            return $tampil;

            
        }

        })
    ->addColumn('action', function($prp) use ($url){
        
        $editform=route('admin.prospectedit',$prp);
        $validasi=route('admin.prospectvalidation',$prp);
        
        $test=$url;
        
        switch($prp->status){
            case(1):
       if($url=="prospect"){
        $theroutes=route('admin.prospecteditdata',(['prospect'=>$prp->id]));
        $btn="<a href='$theroutes' ><h5><span class='badge bg-primary text-light'>Detail Data</span></h5></a>";
       }else{
        $btn = '<div class="row"><a href="'.$editform.'" class="btn aksi  btn-primary btn-sm ml-2 btn-edit">Edit</a> ';
        //$btn .= '<a href="javascript:void(0)" id="'.$prp->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
       }
        return $btn;
        break;
                
            case(99):
            $btn = '<div class="row"><a xid(0)" id="'.$prp->id.'" class="btn btnaksi btn-warning aksi btn-sm ml-2 btn-renew">Renew</a>';
            return $btn;
            break;
        case(404):
            $btn = "Reject Karena ".$prp->rejection->reason;
            return $btn;
            break;
            default:
        
        $btn = '<div class="row"><a href="javascript:void(0)" id="'.$prp->id.'" class="btn aksi btnaksi btn-warning btn-sm ml-2 btn-validasi">Approval</a></div>';
        $btn .= '<div class="row mt-1 mr-1"><a href="javascript:void(0)" id="'.$prp->id.'" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-edit">Edit</a></div>';
        
        return $btn;

        }
         
        })
        
        ->addColumn('validasi',function($prp){
            $validname=User::where('id',$prp->validation_by)->first();
                       
            return $validname?$validname->name:"Belum di Validasi";
          
        })

    //->only(['id','creator','personInCharge',"prospect_no"])
        ->rawColumns(['propdetail','action','promotion',"review",'temperature','statsname',"submitdate","province","anggaran",'hospital','validasi'])
        
        ->toJson();

 }


}

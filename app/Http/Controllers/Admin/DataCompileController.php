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


    function getareaman($isi,$isi2)
    {
        $am=Employee::with('user')->where([['area',$isi],['position','AM']])->get();
        $nsm=Employee::with('user')->where([['area',$isi2],['position','NSM']])->get();
        if($am->count()>0){
       //$dataam= DataTables::of($am)->toJson();
        $useram=$am->pluck('user.name');
        $array = json_decode($useram);
       return $array[0];}
       else if($nsm->count()>0)
       {
        $usernsm=$nsm->pluck('user.name');
         $array = json_decode($usernsm);
       return $array[0];}else return "Tidak ada AM/NSM";
    };


    $status= $request->input('status');

    $url=$request->input('url');
    if($status==1){
    $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
     ->where("status",$status)->orderBy('status','ASC')->orderBy("id",'DESC') 
    ->get()
   
    ;}
    else{
    $prv = Prospect::with("creator","hospital","review","province","department","unit","config","rejection")
    ->where("status",'!=',1)->orderBy('status','ASC')
    ->orderBy("id",'DESC')
     ->get()
   
    ;}
     
  
    //->whereHas('config', function ($query) {
      //  $query->wherePivot('main', 1);
   // })
   

    return DataTables::of($prv)
    ->addIndexColumn()   
    ->addColumn('province', function ($prp) {

    $newprov=$prp->province->name."(".$prp->province->prov_order_no.")";
    $AM= getareaman($prp->province->iss_area_code,$prp->province->wilayah);
  
    $newdata=$newprov."</br>".$AM;
    
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
        ->addColumn('naction', function ($prp) {
            return $prp->review->next_action;
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
            $btn = '<div class="row"><a href="javascript:void(0)" id="'.$prp->id.'" class="btn btnaksi btn-warning aksi btn-sm ml-2 btn-renew">Renew</a>';
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

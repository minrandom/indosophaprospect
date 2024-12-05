<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DataTables;
use Hash;

use App\Models\User;
use App\Models\Employee;
use App\Models\Province;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('employee')->orderBy('created_at','DESC');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<div class="row"><a href="javascript:void(0)" id="'.$row->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                           $btn .= '<a href="javascript:void(0)" id="'.$row->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

                            return $btn;
                    })
                    ->addColumn('Area',function($row){
                        $areacode = $row->employee->area;
                        $role = $row->employee->position;
                        $areaArray = explode(',', $areacode);
                        if($areacode!='HO'){
                            switch($role){
                                case "NSM":
                                    if($areacode !="T")
                                    {$show = "Timur";} else{$show ="Barat";}; 
                                    break;
                                case "AM":
                                    $data = Province::whereIn('iss_area_code', $areaArray)->get();
                                    $show = $data->pluck('name')->implode(', ');
                                break;
                                case "FS":
                                    $data = Province ::where('prov_order_no',$areacode)->first();
                                    $show = $data->name;
                                break;
                                case "FSX":
                                    $data = Province::whereIn('iss_area_code', $areaArray)->get();
                                    $show = $data->pluck('name')->implode(', ');
                                break;
                            }
                        }else {
                        $show = "HEAD OFFFICE";}
                        return $show;

                    })
                    ->addColumn('status', function($row){
                        $role = $row->role;
                        $cek = substr($role,0,2);
                        if($cek!='ex'){$show = "<h4><span class='badge badge-info'>ACTIVE</span></h4>";}else {$show = "<h4><span class='badge badge-secondary'>INACTIVE</span></h4>";}
                        return $show;

                 })
                    
                    ->rawColumns(['action','status'])
                    ->make(true);
        }
        
        return view('admin.user.index');
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {   

        switch($request->role){
            case 'admin':
                $pos = 'ADMIN';
                $area = 'HO';
                break;
            case 'bu':
                $pos = $request->bu??"NOT ISS BU";
                $area = 'HO';
                break;
            case 'am':
                $pos = 'AM';
                $area = $request->area;
                break;
            case 'nsm':
                $pos = 'NSM';
                $area = $request->area;
                break;
            case 'fs':
                $pos = 'FS';
                $area = $request->area;
                break;
            default: $pos = 'ADMIN';
        }


        $employ = Employee::create([
            'longname'=>$request->longname,
            'sex'=>$request->gender,
            'position'=>$pos,
            'area'=>$area,
        ]);



        $request->request->add(['password' => Hash::make($request->password)]);
        
        User::create([
            'employee_id'=>$employ->id,
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'role'=>$request->role,

        ]);
    }

    public function show($id)
    {
        
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function editrole(User $user)
    {
        return response()->json($user);
    }


    public function update(Request $request, User $user)
    {
        $user->update($request->all());
    }

    public function destroy(User $user)
    {
        $user->delete();
    }
}

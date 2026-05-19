<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Department;
use App\Models\installbase;
use App\Models\Province;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use DataTables;
use Illuminate\Support\Facades\Storage;

class InstallbaseController extends Controller
{
    public function index()
    {
        return view('admin.installbase');
    }

    public function ShowMissing()
{
    $table = (new Installbase)->getTable();
    $columns = Schema::getColumnListing($table);

    $ignore = [
        'id','service_contract','type_of_contract','end_of_service','pic_number',
        'created_at','updated_at','pic_job_title','last_review','note_last_review','maintenance_status','repair_status'
    ];
    $columns = array_values(array_diff($columns, $ignore));

    $rowsWithMissing = Installbase::with('hospital')
        ->where(function ($q) use ($columns) {
            foreach ($columns as $column) {
                $q->orWhereNull($column)
                  ->orWhere($column, '')
                  ->orWhere($column, '0000-00-00');
            }
        })
        ->get();

    // Attach missing columns info
    foreach ($rowsWithMissing as $row) {
        $missingCol = [];

        foreach ($columns as $column) {
            $value = $row->$column;

            if ($value === null || $value === '' || $value === '0000-00-00') {
                $missingCol[] = $column;
            }
        }

        $row->missingCol = $missingCol;
        $row->missingColString = implode(', ', $missingCol);
    }

    // Group by hospital id (0 = unknown hospital)
    $grouped = $rowsWithMissing->groupBy(function ($r) {
        return $r->hospital?->id ?? 0;
    });

    return view('admin.installbase_missing_data', compact('grouped', 'columns'));
}

    public function IBData()
    {

        $ibd = installbase::with('product','hospital','hospital.province')->orderBy('id', 'desc')->get();

        return DataTables::of($ibd)

            ->addColumn('action', function ($ibs) {
                $btn = '<div class="row"><a href="javascript:void(0)" id="' . $ibs->id . '" class="btn btn-primary btn-sm ml-2 btn-edit">Flag for Review</a></div>';
                // $btn .= '<a href="javascript:void(0)" id="' . $ibs->id . '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

                return $btn;
            })

             ->addColumn('brand', function ($ibs) {
                $brand = $ibs->product->brand_id;
                $cekbrand = Brand::where('id', $brand)->first();
                //$validname?$validname->name:"Belum di Validasi"
                return $cekbrand ? $cekbrand->name : 'brand not found';
            })
             ->addColumn('category', function ($ibs) {
                $cat = $ibs->product->category_id;
                $cekcat = Category::where('id', $cat)->first();
                //$validname?$validname->name:"Belum di Validasi"
                return $cekcat ? $cekcat->name : 'Category not found';
            })
            ->addColumn('province', function ($ibs) {
                return $ibs->hospital?->province?->name ?? '-';
            })


            ->rawColumns(['action'])
            ->toJson();
    }

    public function updateData($id)
    { //for reuse in every installbase form
        $installbase = installbase::find($id);

        $deptdata = Department::all();;
        $equipmentStatus = [
        ['id' => 'Fully_Operational', 'name' => 'Fully Operational'],
        ['id' => 'Standby_Unit', 'name' => 'Standby Unit'],
        ['id' => 'Under_Repair', 'name' => 'Under Repair'],
        ['id' => 'Not_Working', 'name' => 'Not Working'],
        ['id' => 'Decommissioned', 'name' => 'Decommissioned'],
        ['id' => 'Not_Found', 'name' => 'Not Found'],
    ];

    $warrantyStatus = [
        ['id' => 'No_Cover', 'name' => 'No Cover'],
        ['id' => 'Warranty', 'name' => 'Warranty'],
        ['id' => 'Full_Cover', 'name' => 'Full Cover'],
        ['id' => 'Service_Only', 'name' => 'Service Only'],
        ['id' => 'No_Cover_But_Followed', 'name' => 'No Cover But Followed'],
    ];
        return response()->json([
            'installbase' => $installbase,
            'deptdata' => $deptdata,
            'equipmentStatus' => $equipmentStatus,
            'warrantyStatus' => $warrantyStatus,
        ]);

    }

    public function uploadEquipmentPhoto(Request $request, installbase $installbase, GoogleDriveService $drive)
    {
        $request->validate([
            'photo_data' => ['required', 'string'],
        ]);

        try {
            $photoData = $request->input('photo_data');

            if (!str_contains($photoData, ',')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid photo data.',
                ], 422);
            }

            [$type, $data] = explode(';', $photoData);
            [, $data] = explode(',', $data);

            $data = base64_decode($data);

            $photoFilename = 'label_' . $installbase->id . '_' . uniqid() . '.png';

            $photoUrl = $drive->uploadBase64Image(
                $request->photo_data,
                'label_photo',
                'equipment_label_' . $installbase->id
            );

            $installbase->label_photo = $photoUrl;
            $installbase->save();

            return response()->json([
                'success' => true,
                'message' => 'Label photo uploaded successfully.',
                'photo_url' => $photoUrl,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }



}

<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Config;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createconfdata()
    {
        $unit = Unit::all();
        $brand = Brand::all();
        return response()->json(['unit'=>$unit,'brand'=>$brand]);

    }
    public function create()
    {
        return view('admin.configcreate');  
    }

    public function getProducts(Request $request)
    {
        $businessUnitId = $request->input('businessUnitId');
        $categoryId = $request->input('categoryId');
      
        // Fetch products based on Business Unit and Category IDs
        // Assuming your Product model has 'business_unit_id' and 'category_id' columns
        $products = Config::where('unit_id', $businessUnitId)
            ->where('category_id', $categoryId)
            ->get();

        // Return the products as JSON response
        return response()->json(['products' => $products]);
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
        Config::create([
            'name'=>$request->name,
            'unit_id'=>$request->unit,
            'config_code'=>$request->code,
            'brand_id'=>$request->brand,
            'category_id'=>$request->category,
            'genre'=>$request->Jenis,
            'type'=>$request->tipe,
            'uom'=>$request->uom,
            'consist_of'=>$request->consist,
            'price_include_ppn'=>$request->price,
        ]);
        
        return response()->json(['success'=>true,'messages'=>"Input Config Berhasil"]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function edit(Config $config)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $config)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy(Config $config)
    {
        //
    }
}

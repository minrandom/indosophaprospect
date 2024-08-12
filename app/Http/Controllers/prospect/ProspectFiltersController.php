<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\prospectFilters;

class ProspectFiltersController extends Controller
{
    //

    public function upload(Request $request)
    {
        $userId = auth()->user()->id;
        // Retrieve JSON data from the request
        $filterData = $request->input('filterData');
        // Convert the array to a comma-separated value string
        //$csvString = implode(',', $value);
        var_dump($filterData);
        // Check if the sequence data already exists for the user
        $existingfilter = prospectFilters::where('filterUser', $userId)->first();
    
        if ($existingfilter) {
            // Update existing sequence data
            $existingfilter->update(['filterData' => $filterData]);
        } else {
            // Create new sequence data
            prospectFilters::create([
                'filterUser' => $userId,
                'filterData' => $filterData
            ]);
        }
        // Return a response if necessary
        return response()->json(['message' => 'filter data stored successfully']);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sequence;

class sequenceController extends Controller
{
    //


    public function upload(Request $request)
    {
        $userId = auth()->user()->id;

        // Retrieve JSON data from the request
        $sequenceData = $request->input('sequenceData');
     
      

        // Convert the array to a comma-separated value string
        //$csvString = implode(',', $value);
        var_dump($sequenceData);
        // Check if the sequence data already exists for the user
        $existingSequence = Sequence::where('sequenceUser', $userId)->first();
    
        if ($existingSequence) {
            // Update existing sequence data
            $existingSequence->update(['sequenceData' => $sequenceData]);
        } else {
            // Create new sequence data
            Sequence::create([
                'sequenceUser' => $userId,
                'sequenceData' => $sequenceData
            ]);
        }
    
        // Return a response if necessary
        return response()->json(['message' => 'Sequence data stored successfully']);
    }



}

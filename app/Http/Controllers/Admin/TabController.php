<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Prospect;

class TabController extends Controller
{
    public function loadTabData(Request $request)
    {
      $tab = $request->input('tab');
      $data = [];
  
      // Retrieve data based on the provided tab identifier
      if ($tab === '#tab1') {
        $new = Prospect::with("creator","hospital","review","province","department","unit","config")->get();
        $data= '<div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Default</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
      </div>
      <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-default">Default</span>
      </div>
      <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>'
      
        
        ;
      } elseif ($tab === '#tab2') {
        $data = Prospect::with("creator","hospital","review","province","department","unit","config")->get();
      } elseif ($tab === '#tab3') {
        $data = Prospect::with("creator","hospital","review","province","department","unit","config")->get();
      }
  
      return response()->json($data);
    }
}

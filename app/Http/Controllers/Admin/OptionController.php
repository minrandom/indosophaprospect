<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use Illuminate\Support\HtmlString;
use App\Models\Hospital;
use App\Models\Config;
use App\Models\Prospect;

class OptionController extends Controller
{
    public function province_option(){
        $option=Province::all();
        
        return $option;

    }


}

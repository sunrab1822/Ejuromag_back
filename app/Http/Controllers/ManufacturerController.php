<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(){
        return response()->json(["error" => false, "data" => Manufacturer::all()]);
    }
}

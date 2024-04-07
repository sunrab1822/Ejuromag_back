<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(){
        return response()->json(["error" => false, "data" => Manufacturer::all()]);
    }

    public function ProductByManufacturer($id) {
        $manufwithproducts = Manufacturer::with('product')->where('id', $id)->get();
        return response()->json(["error" => false, "data" => $manufwithproducts]);

    }


}

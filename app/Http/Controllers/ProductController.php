<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAll($id)
    {
        $categorywithproducts = Category::with('product')->where('id', $id)->get();
        return response()->json(["error" => false, "data" => $categorywithproducts]);
    }

    public function getProduct($id){
        $product = Product::where('id', $id)->get();
        if (!$product) {
            return response()->json(["error" => true, "message" => "A termék nem található"]);
        }

        return response()->json(["error" => false, "data" => $product]);

    }





}

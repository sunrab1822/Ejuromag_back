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
        return response()->json($categorywithproducts);
    }



}

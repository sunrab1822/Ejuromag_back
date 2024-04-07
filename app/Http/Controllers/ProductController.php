<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getAll(){
        $products = Product::all();
        return response()->json(["error" => false, "data" => $products]);
    }

    public function getProductsByCategory($id)
    {
        $categorywithproducts = Category::with('product')->where('id', $id)->get();
        return response()->json(["error" => false, "data" => $categorywithproducts]);
    }

    public function getProductById($id){
        $product = Product::where('id', $id)->get();
        if (!$product) {
            return response()->json(["error" => true, "message" => "Product not found."]);
        }

        return response()->json(["error" => false, "data" => $product]);
    }

    public function getProductsByname($name = "")
    {
        $products = Product::where('name', "like", "%$name%" )->get();
        return response()->json(["error" => false, "data" => $products]);

    }



    public function saveProduct(StoreProductRequest $req){

        $product = Product::create([
            "category_id" => $req->category_id,
            "manufacturer_id" => $req->manufacturer_id,
            "name" => $req->name,
            "description" => $req->description,
            "price" => $req->price,
        ]);

        return response()->json(["error" => false, "data" => $product]);
    }

    public function updateProduct($id, StoreProductRequest $req) {

        if (Auth::user()->role != 1){
            return response()->json(["error" => true, "message" => "Unauthorized."]);
        }

        $product = Product::where('id', $id)->first();
        if (!$product) {
            return response()->json(["error" => true, "message" => "Product not found."]);
        }


        $product = Product::where('id', $id)->update([
            "category_id" => $req->category_id,
            "manufacturer_id" => $req->manufacturer_id,
            "name" => $req->name,
            "description" => $req->description,
            "price" => $req->price,
        ]);

        return response()->json(["error" => false, "id" => $product]);
    }



}

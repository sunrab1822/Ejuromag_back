<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSendEmail;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\VarDumper\Cloner\Data;

class OrderController extends Controller
{

    public function getAll(){
        return response()->json(["error" => false, "data" => Order::all()]);
    }

    public function showOrderByuser(){
        $order = Order::with("products")->where("user_id", Auth::id())->get();
        return response()->json(["error" => false, "data" => $order]);
    }


    public function saveOrder(Request $req){
        $user = User::where('email', $req->email)->first();

        $price = 0;
        if ($req->has('address')) {
            $order = Order::create([
               'user_id' => Auth::id(),
               'adress' => $req->address,
               'price' => 0
            ]);

            if ($req->has('products')){
                foreach ($req->products as $value) {
                    OrdersProducts::create([
                        'order_id' => $order->id,
                        'product_id' => $value
                    ]);
                    $price += $this->CalculatePrice($value);
                }

                $order->price = $price;
                $order->save();
		
		$Nametext = $user->name;
                $text = 'Here is your order link.';
                $orderLink =  '/profil/rendelesek';
        
                Mail::to($user->email)->send(new OrderSendEmail($text, $resetLink, $Nametext));

                return response()->json(["error" => false, "data" => $order]);
            }
            else{
                return response()->json(["error" => true, "message" => "Product not found."]);
            }
        }
        else{
            return response()->json(["error" => true, "message" => "Address not found."]);
        }


    }


    public function CalculatePrice($id){
        $product = Product::find($id);
        return $product->price;

    }


}

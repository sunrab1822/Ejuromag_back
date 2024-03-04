<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Mail\ResetTokenEmail;
use App\Models\PasswordResetToken;
use App\Models\ResetPassowrdToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\VarDumper\Cloner\Data;

class AuthController extends Controller
{


    public function Login(LoginRequest $request){

        //$request->validated();

        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json(["message" => "Invalid credentials"], 401);
        }

        $user = User::where('email', $request->email)->first();

        $data = [
            'user' => $user,
            'token' => $user->createToken('API token')->plainTextToken,
        ];

        return response()->json($data);
    }

    public function Register(StoreUserRequest $request){
        //$request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $data = [
            'user' => $user,
            'token' => $user->createToken('API token')->plainTextToken,
        ];

        return response()->json($data, 201);
    }

    public function Logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json(["message" => "Logged out"], 200);
    }

    public function ResetPasswordToken(Request $request) {

        $user = User::where('email', $request->email)->first();

        if (!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }

        $tokenholder = PasswordResetToken::where('email', $user->email)->first();

        if ($tokenholder){
            $expired = Date("Y-m-d H:i", strtotime("15 minutes", strtotime($tokenholder->created_at)));
            $time = Date("Y-m-d H:i", strtotime("60 minutes", strtotime(now())));

            if ($time < $expired){
                return response()->json(["message" => "Email already sent."], 404);
            }
            PasswordResetToken::where('email', $user->email)->delete();
        }

        $resettoken = PasswordResetToken::create([
            'email' => $user->email,
            'token' => Str::random(60)
        ]);

        $text = 'Here is your password reset link.';
        $resetLink =  $resettoken->token;

        Mail::to($user->email)->send(new ResetTokenEmail($text, $resetLink));

        return response()->json(["message" => "Reset token sent."], 200);

    }

    public function ResetPassword(Request $request) {

        $tokenholder = PasswordResetToken::where('token',$request->token)->first();

        if (!$tokenholder){
            return response()->json(["message" =>"Token not found"], 404);
        }

        $user = User::where('email', $tokenholder->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        PasswordResetToken::where('email', $user->email)->delete();

        return response()->json(['message' => 'password changed'], 200);


    }

}

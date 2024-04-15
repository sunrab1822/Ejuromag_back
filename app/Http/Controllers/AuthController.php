<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
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
            return response()->json(["error" => true, "message" => "Invalid credentials"], 401);
        }

        $user = User::where('email', $request->email)->first();

        $data = [
            'user' => $user,
            'token' => $user->createToken('API token')->plainTextToken,
        ];

        return response()->json(["error" => false, "user" => $data]);
    }

    public function Register(StoreUserRequest $request){
        //$request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
        ]);

        $data = [
            'user' => $user,
            'token' => $user->createToken('API token')->plainTextToken,
        ];

        return response()->json(["error" => false, "user" => $data], 201);
    }

    public function Logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json(["error" => false, "message" => "Logged out"], 200);
    }

    public function ResetPasswordToken(Request $request) {

        $user = User::where('email', $request->email)->first();

        if (!$user){
            return response()->json(["error" => true, 'message' => 'User not found.'], 404);
        }

        $tokenholder = PasswordResetToken::where('email', $user->email)->first();

        if ($tokenholder){
            $expired = Date("Y-m-d H:i", strtotime("15 minutes", strtotime($tokenholder->created_at))); /// 15 perces reset token !!!
            $time = Date("Y-m-d H:i", strtotime("60 minutes", strtotime(now())));

            if ($time < $expired){
                return response()->json(["error" => true, "message" => "Email already sent."], 404);
            }
            PasswordResetToken::where('email', $user->email)->delete();
        }

        $resettoken = PasswordResetToken::create([
            'email' => $user->email,
            'token' => Str::random(60)
        ]);

	$Nametext = $user->name;
        $text = 'Here is your password reset link.';
        $resetLink =  $resettoken->token;

        Mail::to($user->email)->send(new ResetTokenEmail($text, $resetLink, $Nametext));

        return response()->json(["error" => false, "message" => "Reset token sent."], 200);

    }

    public function ResetPassword(ResetPasswordRequest $request) {

        $tokenholder = PasswordResetToken::where('token',$request->token)->first();

        if (!$tokenholder){
            return response()->json(["error" => true, "message" =>"Invalid token,"], 404);
        }

        $user = User::where('email', $tokenholder->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        PasswordResetToken::where('email', $user->email)->delete();

        return response()->json(["error" => false,'message' => 'password changed'], 200);


    }

    public function UpdateUser(Request $req)
    {
        $user = User::updateOrCreate([
            "id" => Auth::id()
        ], [
            'name' => $req->name,
            'email' => $req->email,
        ]);

        $user->save();
        return response()->json(["error" => false, 'message' => 'user changed'], 200);
    }

}

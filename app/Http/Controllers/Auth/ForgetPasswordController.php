<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->input('email');
        if(User::where('email',$email)->exists()){
            $token=Str::random(10);
          DB::table('password_resets')->insert(['email'=>$email,'token'=>$token]);
            return response()->json(['message' => 'Request uccessfully made', 'token' => $token]);
       } else
            return response()->json(['message' => 'this user does not exits,please register first']);

    }





}

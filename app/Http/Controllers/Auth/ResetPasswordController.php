<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function passwordReset(Request $request)

    {
        $request->validate([
            'token' => 'required|string|exists:password_resets,token',
            'email' => 'required|email|exists:users,email'

        ]);

        $token  = $request->input('token');
        $email  = $request->input('email');


        if (!DB::table('password_resets')->where('token', $token)->exists() && User::where('email', $email)->exists()) {

            return response()->json(['message' => " token  or email is  invalid ,please request a new token",]);
        }
        if (User::where('email', $email)->update(['password' => Hash::make($request->password)])) {
            DB::table('password_resets')->where(['email' => $request->email])->delete();

            return response()->json(['status' => true, 'message' => "your password is successfully change",]);
        }
    }
}

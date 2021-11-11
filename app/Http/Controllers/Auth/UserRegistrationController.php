<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        //   event(new Registered($user));

        $token = $user->createToken('pass')->accessToken;
        return response()->json([
            "success" => true,
            "message" => 'The Registration was successfull',
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|',
        ]);

        $credentials = $request->only(['email', 'password' => Hash::make($request->password)]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token =  $user->createToken('pass')->accessToken;
            return response()->json(['success' => true, "message" => 'login successfully', 'token' => $token,]);
        } else
            return response()->json([
                'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.',
            ]);
    }
}

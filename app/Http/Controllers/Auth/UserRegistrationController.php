<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

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
            'phone' => ' required|digits:10',
            'surname' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
        ]);
        //   event(new Registered($user));

       // $token = $user->createToken('pass')->accessToken;
        return response()->json([
            "success" => true,
            "message" => 'The Registration was successful',
           // 'token' => $token
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($request->only("email", "password"))) {
            /*  @var User $user*/
            $user = Auth::user();

            $token =  $user->createToken('pass')->accessToken;
            return response()->json(['success' => true, "message" => 'login successfully', 'token' => $token], 200);
        } else
            return response()->json(
            [
                "success" => false, "message" => 'The given data was invalid', "password" => 'The password that you have enter is wrong'
            ],
            422
        );
         //   {"message":"The given data was invalid.","errors":{"email":["The selected email is invalid."]}
    }
    public function getUser()
 {
     return Auth::user();
 }
 /**
     * Change the current password
     * @param Request $request
     * @return Renderable
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $userPassword = $user->password;

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);

        if (!Hash::check($request->current_password, $userPassword)) {
            return response()->json(["message" => 'The given data was invalid', 'current_password' => 'password not match'], 401);
        }

        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['Message'=>'password successfully updated',"success"=>true],200);
    }



    public function profileUpdate(Request $request)
    {
        //validation rules

        $request->validate(['name' => 'string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'phone' => ' digits:10',
            'surname' => 'string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->surname = $request->get('surname');
        $user->save();
        return response()->json(['message'=>'Profile suceesfully Updated',"success"=>true],200);
    }


}

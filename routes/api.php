<?php


use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserRegistrationController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', [UserRegistrationController::class,'getUser'])->middleware('auth:api');
Route::put('/change_password', [UserRegistrationController::class,'changePassword'])->middleware('auth:api');
Route::put('/profile_update', [UserRegistrationController::class,'profileUpdate'])->middleware('auth:api');

//Route::resource('/task', TaskController::class);
Route::get('/task/{task}', [TaskController::class,'getTaskById']);
Route::get('/task', [TaskController::class,'getAllTasks']);
Route::put('/task/{task}', [TaskController::class,'updateTask']);
Route::delete('/task/{task}', [TaskController::class,'deleteTask']);

Route::post('/task', [TaskController::class,'createTask']);
Route::delete('/delete/{task} ', [TaskController::class,'forceDeleted']);


Route::post('/register', [UserRegistrationController::class,'register']);
Route::post('/login', [UserRegistrationController::class,'login'])->name('login');

Route::post('/forgotten_password',[ForgetPasswordController::class,'forgetPassword'])->name('password.request');
Route::post('/reset_password',[ResetPasswordController::class,'passwordReset'])->name('password.reset');


// Verify email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Resend link to verify email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

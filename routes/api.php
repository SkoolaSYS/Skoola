<?php

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReceiveController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\EwalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register' , RegisterController::class);
Route::get('student/{student_id}' , [StudentController::class , 'index']);
Route::get('user/{user_id}' , [UserController::class , 'index']);
Route::post('/receive', [ReceiveController::class, 'store'])->name('receive.store');
Route::post('/chat/ask', [ChatController::class, 'ask']);
Route::get('/ewallet/parent-by-email', [
    EwalletController::class,
    'getParentByEmail'
]);


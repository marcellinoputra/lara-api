<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/users/sign-up', [UserController::class, 'signUp']);
Route::post('/users/sign-in', [UserController::class, 'signIn']);


Route::middleware(ApiAutMiddleware::class)->group(function () {
    Route::get('/users/get-current-user', [UserController::class, 'getUser']);
    Route::put('/users/update/{id}', [UserController::class, 'updateUser']);
});

Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser']);

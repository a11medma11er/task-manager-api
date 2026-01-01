<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Resources\UserResource;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user()->load(['roles', 'permissions']);
    return new UserResource($user);
});


Route::get('/auth/test', [AuthentController::class, 'test']);
Route::post('/auth/register', [AuthentController::class, 'register']);
Route::post('/auth/login', [AuthentController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthentController::class, 'logout']);

// Task routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->middleware('permission:view tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('permission:create tasks');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->middleware('permission:view tasks');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->middleware('permission:edit tasks');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('permission:delete tasks');
});



<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

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

Route::post('login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){

    Route::controller(EmployeeController::class)->group(function () {
        Route::post('employee-store', 'store');
        Route::get('employees', 'index');
        Route::get('employees/{id}', 'view');
    });
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('attendances', 'index');
        Route::post('attendance-store', 'store');
    });
});




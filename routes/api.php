<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;

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
    return $request->user();
});

//token=WW123
Route::middleware(['ApiTokenMiddleware'])->group(function () {
Route::get('/get_products', [ApiProductController::class, 'get_products']);
});
// Route::middleware('auth:sanctum')->get('/get_products', [ApiProductController::class, 'get_products']);


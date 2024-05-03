<?php

use App\Http\Controllers\API\V1\GetDataController;
use App\Http\Controllers\API\V1\GetFPSController;
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


Route::get('/data', [GetDataController::class, 'getAlldata'])->name('game');
Route::post('/fps', [GetFPSController::class, 'getFPS'])->name('fps');

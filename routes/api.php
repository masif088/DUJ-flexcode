<?php

use App\Http\Controllers\Api\AfterController;
use App\Http\Controllers\Api\MasukController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\CheckController;
use App\Http\Controllers\Api\InfraController;
use App\Http\Controllers\Api\MutasiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MonitorController;

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

Route::middleware('auth:api')->get('/null', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('user')->group(function () {
        Route::post('/create',[ApiUserController::class,'store']);
        Route::put('/edit',[ApiUserController::class,'edit']);
        Route::put('/delete',[ApiUserController::class,'delete']);
    });
    Route::prefix('monitor')->group(function () {
        Route::get('/', [MonitorController::class, 'index']);
    });
    Route::prefix('masuk')->group(function () {
        Route::get('/riwayat',[MasukController::class,'riwayat']);
        Route::post('/create',[MasukController::class,'store']);
        Route::post('/detail',[MasukController::class,'detail']);
    });
    Route::prefix('barang')->group(function () {
        Route::get('/',[BarangController::class,'index']);
        Route::get('/status/terjual',[BarangController::class,'status']);
        Route::post('/terjual',[BarangController::class,'terjual']);


    });
    Route::prefix('mutasi')->group(function () {
        Route::get('/riwayat',[MutasiController::class,'riwayat']);
        Route::post('/create',[MutasiController::class,'store']);
        Route::post('/batal/{id}',[MutasiController::class,'batal']);
        Route::post('/hapus/{id}',[MutasiController::class,'delete']);
        Route::post('/detail',[MutasiController::class,'detail']);
        Route::get('/reset', [MutasiController::class, 'reset']);
    });
    Route::prefix('gudang')->group(function () {
        Route::get('/',[MutasiController::class,'gudang']);
    });
    Route::prefix('check')->group(function () {
        Route::get('/riwayat',[CheckController::class,'riwayat']);
        Route::get('/start',[CheckController::class,'start']);
        Route::post('/detail',[CheckController::class,'detail']);
        Route::post('/create/{id}',[CheckController::class,'store']);
    });
    Route::prefix('infra')->group(function () {
        Route::get('/',[InfraController::class,'index']);
        Route::post('/scan',[InfraController::class,'scan']);
        Route::post('/service',[InfraController::class,'service']);
    });
    Route::prefix('after')->group(function () {
        Route::get('/',[AfterController::class,'index']);
        Route::post('/create',[AfterController::class,'store']);
        Route::post('/service',[AfterController::class,'service']);
    });
});
Route::get('/isAuth', [ApiUserController::class, 'isAuth']);
Route::post('/login', [ApiUserController::class, 'login']);

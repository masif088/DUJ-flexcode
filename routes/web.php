<?php

use App\Http\Controllers\AfterController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\InfraController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MasukController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ServiceAfterController;
use App\Http\Controllers\ServiceInfraController;
use App\Http\Controllers\SuplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/loginn', 'frontend.auth.login');
Route::view('/registerr', 'frontend.auth.register');
Auth::routes();

// backend
Route::group(['middleware' => ['auth', 'CheckRole:admin,head,ketua,checker,teknisi']], function () {
    Route::get('/finger-login',function (){
        return view('fingers.auth');
    });
//    Route::get('/finger/{action}/',function ($action){
//        $data=['action'=>$action];
//        return view('fingers.index', compact('data'));
//    })->name('fingers');

    Route::get('/finger/{action}/{id?}',function ($action,$id=null){
        if ($id==null) {
            $data = ['action' => $action];
        }else{
            $data = ['action' => $action,'id'=>$id];
        }
        return view('fingers.index', compact('data'));
    })->name('fingers');

    Route::get('/set-finger/{action}/{id?}',function ($action,$id=''){
        if ($id==null) {
            $action = \route($action);
        }else{
            $action = \route($action,$id);
        }
        return view('fingers.blank',compact('action'));
    })->name('setAction');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::group(['middleware' => ['CheckRole:admin,head,ketua']], function () {
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/create', [UserController::class, 'store']);
            Route::delete('/delete', [UserController::class, 'delete']);
        });
        Route::group(['middleware' => ['CheckRole:admin']], function () {
            Route::get('/all', [UserController::class, 'all'])->name('all');
            Route::put('/edit/{id}', [UserController::class, 'update'])->name('lihat');

        });
    });
    Route::group(['middleware' => ['CheckRole:head,admin,teknisi']], function () {
        Route::prefix('barang')->name('barang.')->group(function () {
            Route::post('/create', [BarangController::class, 'store']);
            Route::get('/edit/{id}', [BarangController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [BarangController::class, 'update']);
            Route::delete('/delete/{id}', [BarangController::class, 'delete'])->name('delete');
        });
        Route::prefix('gudang')->name('gudang.')->group(function () {
            Route::get('/create', [GudangController::class, 'create'])->name('create');
            Route::post('/create', [GudangController::class, 'store']);
            Route::get('/edit/{id}', [GudangController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [GudangController::class, 'update']);
            Route::delete('/delete/{id}', [GudangController::class, 'delete'])->name('delete');
        });
        Route::prefix('suplier')->name('suplier.')->group(function () {
            Route::get('/create', [SuplierController::class, 'create'])->name('create');
            Route::post('/create', [SuplierController::class, 'store']);
            Route::get('/edit/{id}', [SuplierController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [SuplierController::class, 'update']);
            Route::delete('/delete/{id}', [SuplierController::class, 'delete'])->name('delete');
        });
        Route::prefix('masuk')->name('masuk.')->group(function () {
            Route::get('/create', [MasukController::class, 'create'])->name('create');
            Route::post('/create', [MasukController::class, 'store']);
            Route::get('/edit/{id}', [MasukController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [MasukController::class, 'update']);
            Route::delete('/delete', [MasukController::class, 'delete']);
        });
        Route::prefix('barcode')->name('barcode.')->group(function () {
            Route::get('/create', [BarcodeController::class, 'create'])->name('create');
            Route::post('/create', [BarcodeController::class, 'store']);
            Route::get('/aktifasi', [BarcodeController::class, 'edit'])->name('edit');
            Route::put('/aktifasi', [BarcodeController::class, 'update']);
            Route::get('/terjual/{list?}', [BarcodeController::class, 'jual'])->name('terjual');
            Route::put('/terjual', [BarcodeController::class, 'terjual']);
            Route::delete('/delete', [BarcodeController::class, 'delete']);
        });
        Route::prefix('mutasi')->name('mutasi.')->group(function () {
            Route::get('/', [MutasiController::class, 'index'])->name('index');
            Route::get('/create', [MutasiController::class, 'create'])->name('create');
            Route::post('/create', [MutasiController::class, 'store']);
            Route::get('/edit/{id}', [MutasiController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [MutasiController::class, 'update']);
            Route::get('/batal/{id}', [MutasiController::class, 'batal'])->name('batal');
            Route::get('/reset', [MutasiController::class, 'reset'])->name('reset');
            Route::get('/invoice/{id}', [MutasiController::class, 'invoice'])->name('invoice');
            Route::get('/delete/{id}', [MutasiController::class, 'delete'])->name('delete');

        });
        Route::prefix('infra')->name('infra.')->group(function () {
            Route::get('/', [InfraController::class, 'index'])->name('index');
            Route::get('/create', [InfraController::class, 'create'])->name('create');
            Route::post('/create', [InfraController::class, 'store']);
            Route::get('/edit/{id}', [InfraController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [InfraController::class, 'update']);
            Route::delete('/delete/{id}',[InfraController::class, 'delete'])->name('delete');
            Route::get('/barcode/{b}', [InfraController::class, 'barcode'])->name('barcode');

        });
        Route::prefix('service-infra')->name('serviceInfra.')->group(function () {
            Route::get('/', [ServiceInfraController::class, 'index'])->name('index');
            Route::get('/create', [ServiceInfraController::class, 'create'])->name('create');
            Route::post('/create', [ServiceInfraController::class, 'store']);
            Route::get('/edit/{id}', [ServiceInfraController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [ServiceInfraController::class, 'update']);
            Route::delete('/delete/{id}',[ServiceInfraController::class, 'delete'])->name('delete');
            Route::get('/barcode/{b}', [ServiceInfraController::class, 'barcode'])->name('barcode');
            Route::get('/batal/{id}', [ServiceInfraController::class, 'batal'])->name('batal');
            Route::get('/setuju/{id}', [ServiceInfraController::class, 'setuju'])->name('setuju');
            Route::post('/tolak/{id}', [ServiceInfraController::class, 'tolak'])->name('tolak');

        });
        Route::prefix('after')->name('after.')->group(function () {
            Route::get('/', [AfterController::class, 'index'])->name('index');
            Route::get('/create', [AfterController::class, 'create'])->name('create');
            Route::post('/create', [AfterController::class, 'store']);
            Route::get('/edit/{id}', [AfterController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [AfterController::class, 'update']);
            Route::delete('/delete/{id}',[AfterController::class, 'delete'])->name('delete');
            Route::get('/barcode/{b}', [AfterController::class, 'barcode'])->name('barcode');
            Route::get('/setuju/{id}', [AfterController::class, 'setuju'])->name('setuju');
            Route::post('/tolak/{id}', [AfterController::class, 'tolak'])->name('tolak');

        });
        Route::prefix('service-after')->name('serviceAfter.')->group(function () {
            Route::get('/', [ServiceAfterController::class, 'index'])->name('index');
            Route::get('/edit/{id}', [ServiceAfterController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [ServiceAfterController::class, 'update']);
            Route::delete('/delete/{id}',[ServiceAfterController::class, 'delete'])->name('delete');
            Route::get('/batal/{id}', [ServiceAfterController::class, 'batal'])->name('batal');
        });
    });
    Route::group(['middleware' => ['CheckRole:admin,head']], function () {
        Route::prefix('barang')->name('barang.')->group(function () {
            Route::get('/', [BarangController::class, 'index'])->name('index');
            Route::get('/detail/{id?}', [BarangController::class, 'detail'])->name('detail');
            Route::get('/create', [BarangController::class, 'create'])->name('create');
        });
        Route::prefix('gudang')->name('gudang.')->group(function () {
            Route::get('/', [GudangController::class, 'index'])->name('index');
        });
        Route::prefix('suplier')->name('suplier.')->group(function () {
            Route::get('/', [SuplierController::class, 'index'])->name('index');
        });
        Route::prefix('masuk')->name('masuk.')->group(function () {
            Route::get('/', [MasukController::class, 'index'])->name('index');
        });
        Route::prefix('barcode')->name('barcode.')->group(function () {
            Route::get('/{id}', [BarcodeController::class, 'index'])->name('index');
        });
    });
    Route::group(['middleware' => ['CheckRole:admin']], function () {
        Route::prefix('log')->name('log.')->group(function () {
            Route::get('/', [LogController::class, 'index'])->name('index');
        });
    });

});
Route::get('/profil', function () {
    return view('mutasi.tambah-mutasi');
});

Route::get('/barang/barang', function () {
    return view('frontend.barang.barang');
});
Route::get('/barang_masuk', function () {
    return view('frontend.barang.list-barang');
});
Route::get('/list-masuk', function () {
    return view('frontend.barang.p');
});
Route::get('/p', function () {
    return view('frontend.barang.p');
});
// Route::get('/keluar', function () {
//     return view('frontend.barang.keluar.index');
// });
// barang keluar
Route::get('/list', function () {
    return view('frontend.barang.keluar.list');
});
Route::get('/keluar-create', function () {
    return view('frontend.barang.keluar.create');
});
Route::get('/keluar-edit', function () {
    return view('frontend.barang.keluar.create');
});
///end barang keluar

//infrastruktur
Route::get('/infrastruktur', function () {
    return view('frontend.infrastruktur.infra');
});
Route::get('/infrastruktur-create', function () {
    return view('frontend.infrastruktur.create');
});
Route::get('/infrastruktur-edit', function () {
    return view('frontend.infrastruktur.edit');
});
//end infra

//service
Route::get('/service', function () {
    return view('frontend.service.service');
});
Route::get('/service-teknisi', function () {
    return view('frontend.service.service-r-teknisi');
});
Route::get('/service-a-teknisi', function () {
    return view('frontend.after.index-teknisi');
});
//after
// Route::get('/after', function () {
//     return view('frontend.after.index');
// });

 //list user
 Route::get('/list', function () {
     return view('frontend.list-user.list');
 });
 //semua barang
 Route::get('/semua', function () {
     return view('frontend.barang.semua-barang');
 });
//aktif barcode
Route::get('/aktif-bar', function () {
    return view('frontend.aktif.barcode');
});
//dashboard
Route::get('/dashboard', function () {
    return view('frontend.dashboard.HO');
});
Route::get('/dashboard_admin', function () {
    return view('frontend.dashboard.Admin');
});
Route::get('/dashboard_teknisi', function () {
    return view('frontend.dashboard.Teknisi');
});

//invoice
Route::get('/invoice', function () {
    return view('frontend.invoice.invoice');
});

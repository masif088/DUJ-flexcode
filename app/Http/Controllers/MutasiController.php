<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mutasi\StoreRequest;
use App\Models\Barcode;
use App\Models\Gudang;
use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Services\Barcode\BarcodeService;
use Services\Mutasi\MutasiService;

class MutasiController extends Controller
{
    public function __construct()
    {
        $this->log = new LogController;
    }
    public function index(Request $request)
    {
        if(auth()->user()->role != 'admin'){
            $mutasis = Mutasi::whereHas('barcode', function ($m) {
                return $m->whereHas('masuk', function($z){
                    return $z->where('gudang_id',auth()->user()->gudang_id);
                });
            })->groupby('kode_mutasi')->orderByDesc('created_at')->paginate(30);
        }else{
            if($request->mutasi != null){
                $mutasis = Mutasi::where('id',$request->mutasi)->groupby('kode_mutasi')->orderByDesc('created_at')->paginate(30);
            }else{
                $mutasis = Mutasi::groupby('kode_mutasi')->orderByDesc('created_at')->paginate(30);

            }
        }
        return view('mutasi.list', compact('mutasis'));
    }
    public function invoice($id)
    {
        $mutasi = Mutasi::where('kode_mutasi',$id)->get();
        return view('mutasi.invoice',compact('mutasi'));
    }
    public function create()
    {
        $gudang = Gudang::get();
        $zz = Mutasi::where('kode_mutasi',Cookie::get('kodeMts'));
        $b = (clone $zz)->count();
        if($zz->exists()){
            $g = (clone $zz)->first()->gudang;
            $gud = (clone $zz)->get();
        }else{
            $gud = [];
            $g = 'null';
        }
        return view('mutasi.create', compact(['g','b','gudang','gud']));
    }
    public function reset()
    {
       $c = Cookie::forget('kodeMts');
       return redirect()->back()->withCookie($c);
    }
    public function store(StoreRequest $request)
    {
//        if (isset($request->validator) && $request->validator->fails()) {
//            dd($request->validator->messages());
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        $data = BarcodeService::find($request->kode,null,auth()->user()->gudang_id);
//        if ($data == null){
//            toastr()->warning('Tidak ditemukan');
//             return redirect()->back();
//        }
//        if ($data->status == 'nonaktif' || $data->status == 'mutasi'){
//            toastr()->warning('barang masih nonaktif/telahh termutasi');
//            return redirect()->back();
//        }
//        $c = DB::transaction(function() use($data,$request){
//            BarcodeService::update($data, 'mutasi');
//            $c = MutasiService::store($request, $data->id);
//            return $c;
//        });
//        toastr()->success('Berhasil');
//        return redirect()->back()->cookie($c);
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = BarcodeService::find($request->kode, null, auth()->user()->gudang_id);
        if ($data == null) {
            toastr()->warning('Tidak ditemukan');
            return redirect()->back();
        }
        if ($data->status == 'nonaktif' || $data->status == 'mutasi') {
            toastr()->warning('barang masih nonaktif/telahh termutasi');
            return redirect()->back();
        }
        $b = $data->id;
        $datas = $request->all();
        if (is_null(Cookie::get('kodeMts'))) {
            $coki = 0;
            do {
                $rk = Str::random(5 + $coki);
                $c = cookie("kodeMts", $rk, 60000);
                $coki += 1;
            } while (Mutasi::where('kode_mutasi', $rk)->exists());
        } else {
            $rk = Cookie::get('kodeMts');
            $c = cookie("kodeMts", $rk, 60000);
            $dd = Mutasi::where('kode_mutasi', $rk)->first();
            $datas['gudang'] = $dd->gudang_id ?? $datas->gudang;
        }


        $data = [
            "action" => 'mutasi.store',
            'user_id'=>Auth::id(),
            'gudang_id' => $data->gudang,
            'barcode_id' => $b,
            'kode_mutasi' => $rk,
        ];
        return view('fingers.index', compact('data'));
    }
    public function edit(Mutasi $id)
    {
        $gudang = Gudang::get();
        return view('mutasi.edit', compact(['gudang', 'id']));
    }
    public function update(StoreRequest $request, Mutasi $id)
    {
//        if (isset($request->validator) && $request->validator->fails()) {
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        $data = BarcodeService::find($request->kode);
//        if ($data == null) return 'tidak ditemukan';
//        if ($data->status == 'nonaktif') return 'barang masih nonaktif';
//        if ($data->status == 'mutasi' && $data->kode != $id->barcode->kode) return 'barang telah termutasi';
//        DB::transaction(function() use($data,$id,$request){
//            if ($data->kode != $id->barcode->kode) {
//                BarcodeService::update($id->barcode(), 'aktif');
//            }
//            MutasiService::update($id, $data->id, $request->gudang);
//            BarcodeService::update($data, 'mutasi');
//        });
//        toastr()->success('Berhasil');
//
//        return redirect()->back();
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = BarcodeService::find($request->kode);
        if ($data == null) return 'tidak ditemukan';
        if ($data->status == 'nonaktif') return 'barang masih nonaktif';
        if ($data->status == 'mutasi' && $data->kode != $id->barcode->kode) return 'barang telah termutasi';
        $data = [
            'action' => 'after.store',
            'barcode_services_id' => $data->id,
            'id' => $id->id,
            'user_id'=>Auth::id(),
            'gudang_id' => $request->gudang
        ];
        return view('fingers.index', compact('data'));
    }
    public function batal( $id)
    {
//        DB::transaction(function() use($id){
//            $m = Mutasi::where('kode_mutasi',$id->kode_mutasi)->get();
//            foreach ($m as $idd) {
//                MutasiService::batal($idd);
//                BarcodeService::update($idd->barcode(), 'aktif');
//            }
//            $this->log->create('membatalkan mutasi #'.$idd->kode,'mutasi',$idd->id);
//
//        });
//        toastr()->success('Berhasil');
//
//        return redirect()->back();
        $data = [
            'id' => $id,
            'user_id'=>Auth::id(),
            'action' => 'mutasi.batal'
        ];
        return view('fingers.index', compact('data'));
    }
    public function delete(Mutasi $id)
    {
//        $id->delete();
//        BarcodeService::update($id->barcode(), 'aktif');
//        return redirect()->back();
        $data = [
            'id' => $id,
            'user_id'=>Auth::id(),
            'action' => 'mutasi.delete'
        ];
        return view('fingers.index', compact('data'));
    }
}

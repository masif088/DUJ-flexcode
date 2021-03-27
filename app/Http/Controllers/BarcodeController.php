<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Services\Barcode\BarcodeService;

class BarcodeController extends Controller
{
    public function __construct()
    {
        $this->log = new LogController;
    }
    public function index($id)
    {
        $barcode = Barcode::where('masuk_id',$id)->get();
        return view('backend.barcode',compact('barcode'));
    }
    public function edit()
    {

        return view('barcode.barcode');
    }
    public function update(Request $request)
    {
//        $data = BarcodeService::find($request->kode,'nonaktif',auth()->user()->gudang_id);
//        if ($data == null) {
//            toastr()->warning('barang telah aktif/termutasi');
//            return redirect()->back();
//        }
//        if ($data->status == 'aktif' || $data->status == 'mutasi'){
//            toastr()->warning('barang telah aktif/termutasi');
//            return redirect()->back();
//        }
//        BarcodeService::update($data,'aktif');
//        toastr()->success('Berhasil');
//
//        $this->log->create('aktifasi barcode #'.$data->kode,'barcode',$data->id);
//        return redirect()->back();
        $data = BarcodeService::find($request->kode,'nonaktif',auth()->user()->gudang_id);
        if ($data == null) {
            toastr()->warning('barang telah aktif/termutasi');
            return redirect()->back();
        }
        if ($data->status == 'aktif' || $data->status == 'mutasi'){
            toastr()->warning('barang telah aktif/termutasi');
            return redirect()->back();
        }
        $data=[
            'action'=> 'barcode.update',
            'user_id'=>Auth::id(),
            'id'=>$data->id
        ];
        return view('fingers.index', compact('data'));
    }
    public function jual($list = null)
    {
        if(auth()->user()->role == 'admin' || ($list == 'all')){
            $barang = Barcode::where('status','terjual')->orderByDesc('created_at')->paginate(30);
            return view('barang.terjual',compact('barang'));
        }
        return view('barcode.terjual');
    }
    public function terjual(Request $request)
    {
//        $data = BarcodeService::find($request->kode,'aktif',auth()->user()->gudang_id);
//        if ($data == null || $data->status != 'aktif'){
//            toastr()->warning('barang belum aktif');
//            return redirect()->back();
//        }
//        BarcodeService::update($data,'terjual');
//        toastr()->success('Berhasil');
//        $this->log->create('status barcode terjual #'.$data->kode,'barcode',$data->id);
//        return redirect()->back();
        $data = BarcodeService::find($request->kode,'aktif',auth()->user()->gudang_id);
        if ($data == null || $data->status != 'aktif'){
            toastr()->warning('barang belum aktif');
            return redirect()->back();
        }
        $data=[
            'action'=> 'barcode.terjual',
            'user_id'=>Auth::id(),
            'id'=>$data->id
        ];
        return view('fingers.index', compact('data'));
    }
}

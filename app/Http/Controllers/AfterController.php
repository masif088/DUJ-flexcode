<?php

namespace App\Http\Controllers;

use App\Http\Requests\After\StoreRequest;
use App\Models\After;
use App\Models\ServiceAfter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Services\Barcode\BarcodeService;

class AfterController extends Controller
{
    public function __construct()
    {
        $this->fcm = new FcmController;
        $this->log = new LogController;
    }
    public function index(Request $request)
    {
        if($request->after != null){
            $after = After::where('id',$request->after)->orderByDesc('created_at')->paginate(10);
        }elseif($request->safter != null){
            $after = ServiceAfter::where('id',$request->safter)->first()->after()->paginate(10);
        }else{
            $after = After::orderByDesc('created_at')->paginate(10);

        }
        return view('service.after.index',compact('after'));
    }
    public function create()
    {
        return view('backend.after.create');
    }
    public function store(StoreRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = BarcodeService::find($request->kode, 'terjual');
        if ($data == null) {
            toastr()->warning('maaf kode yang anda masukan salah');
        }
        if(After::where('barcode_id',$data->id)->whereHas('serviceAfters',function($x){
            return $x->where('status','pengajuan')->orWhere('status','tidak');
        })->exists()){
            toastr()->warning('maaf barang anda sedang dalam proses pengajuan atau perbaikan');
            return redirect()->back();
        }
        if ($request->file == null) {
            toastr()->warning('maaf file yang anda masukan salah');
        }
        if ($data == null || $request->file == null) {
            return redirect()->back();
        }
        $fileName = null;
        $file = $request->file('file');
        $fileName = substr(md5(microtime()), 0, 100) . '.' . $file->getClientOriginalExtension();
        $request->file('file')->storeAs('public/after/', $fileName);

        if (After::where('barcode_id', $data->id)->exists()) {

            $after = After::where('barcode_id', $data->id)->first();
            $data = [
                'action' => 'after.store',
                'if' => 'true',
                'after_id' => $after->id,
                'deskripsi' => $request->deskripsi,
                'file' => $fileName,
                'user_id' => auth()->user()->id,
            ];
        } else {
            $data = [
                'action' => 'after.store',
                'if' => 'false',
                'user_id' => auth()->user()->id,
                'barcode_id' => $data->id,
                'gudang_id' => auth()->user()->gudang_id,
                'nama_pembeli' => $request->nama_pembeli,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'deskripsi' => $request->deskripsi,
                'file' => $fileName
            ];
        }
        return view('fingers.index', compact('data'));
    }
    public function edit(After $id)
    {
        return view('backend.after.editafter',compact('id'));
    }
    public function update(Request $request,$id)
    {
        $data = BarcodeService::find($request->kode);
        if ($data == null) return 'tidak ditemukan';
        if ($data->status == 'nonaktif') return 'barang masih nonaktif';
        $data = [
            'id'=>$id,
            'action' => 'after.update',
            'user_id' => auth()->user()->id,
            'gudang_id' => auth()->user()->gudang_id,
            'barcode_id' => $data->id,
            'nama_pembeli' => $request->nama_pembeli
        ];
        return view('fingers.index', compact('data'));
    }
    public function setuju($id)
    {
//        $id->serviceAfter()->update([
//            'status' => 'tidak',
//        ]);
//        toastr()->success('Berhasil');
//
//        $this->log->create('Persetujuan after sale','after',$id->id);
//        $this->fcm->send(' AfterSale','Selamat persetujuan telah diterima',null,null,null,$id->gudang_id);
//        return redirect()->back();
        $data=[
            'id'=>$id,
            'action'=>'after.setuju',
            'user_id'=>Auth::id()
        ];
        return view('fingers.index', compact('data'));
    }
    public function tolak(Request $request,$id)
    {
//        $id->serviceAfter()->update([
//            'status' => 'tolak',
//            'alasan' => $request->alasan
//        ]);
//        toastr()->success('Berhasil');
//
//        $this->fcm->send('Persetujuan AfterSale','Persetujuan anda ditolak',null,null,null,$id->gudang_id);
//        $this->log->create('Persetujuan after sale ditolak','after',$id->id);
//        return redirect()->back();
        $data=[
            'id'=>$id,
            'action'=>'after.tolak',
            'alasan'=>$request->alasan,
            'user_id'=>Auth::id()
        ];
        return view('fingers.index', compact('data'));
    }
}

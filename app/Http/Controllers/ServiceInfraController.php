<?php

namespace App\Http\Controllers;

use App\Models\Infra;
use App\Models\ServiceInfra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceInfraController extends Controller
{
    public function __construct()
    {
        $this->fcm = new FcmController;
        $this->log = new LogController;

    }
    public function index(Request $request)
    {
        if($request->infra != null){
            $service = ServiceInfra::where('id',$request->infra)->orderByDesc('created_at')->paginate(10);
        }else{
            $service = ServiceInfra::orderByDesc('created_at')->paginate(10);

        }
        return view('service.infra.index',compact('service'));
    }
    public function store(Request $request)
    {
//        $id = Infra::where('kode',$request->kode)->first();
//        if($id == null){
//            toastr()->warning('maaf kode yang anda masukan salah');
//        }
//        if($request->file == null){
//            toastr()->warning('maaf file yang anda masukan salah');
//        }
//        if($id == null || $request->file == null){
//            return redirect()->back();
//        }
//        $fileName = null;
//            $file = $request->file('file');
//            $fileName = substr(md5(microtime()), 0, 100).'.'.$file->getClientOriginalExtension();
//            $request->file('file')->storeAs('public/infra/',$fileName);
//
//        $id->update([
//            'status' => 'rusak',
//            ]);
//        $ss = ServiceInfra::create([
//            'file' => $fileName,
//            'deskripsi' => $request->deskripsi,
//            'infra_id' => $id->id
//        ]);
//        $this->log->create('membuat service infrastruktur #'.$request->kode,'service_infra',$ss->id);
//
//        toastr()->success('Berhasil');
//        return redirect()->back();
        $id = Infra::where('kode',$request->kode)->first();
        if($id == null){
            toastr()->warning('maaf kode yang anda masukan salah');
        }
        if($request->file == null){
            toastr()->warning('maaf file yang anda masukan salah');
        }
        if($id == null || $request->file == null){
            return redirect()->back();
        }
        $fileName = null;
        $file = $request->file('file');
        $fileName = substr(md5(microtime()), 0, 100).'.'.$file->getClientOriginalExtension();
        $request->file('file')->storeAs('public/infra/',$fileName);

        $data = [
            "action" => 'serviceInfra.store',
            'file' => $fileName,
            'deskripsi' => $request->deskripsi,
            'infra_id' => $id->id,
            'kode'=>$request->kode,
            'user_id'=>Auth::id(),
        ];
        return view('fingers.index', compact('data'));
    }
    function edit(ServiceInfra $id)
    {
        return view('service.infra.perbaiki',compact('id'));
    }
    public function update(Request $request, $id)
    {
//        $id->update([
//            'user_id' => auth()->user()->id,
//            'lama' => $request->lama,
//            'sparepart' => $request->sparepart,
//            'status' => $request->status ?? 'tidak'
//        ]);
//        if($id->status == 'selesai'){
//            $id->infra->update([
//                'status' => 'ready'
//            ]);
//            $this->log->create('menyelesaikan service infrastruktur #'.$id->infra->kode,'service_infra',$id->id);
//        }else{
//            $this->log->create('perubahan service infrastruktur #'.$id->infra->kode,'service_infra',$id->id);
//
//        }
//        return redirect()->back();
        $data = [
            "action" => 'serviceInfra.update',
            'user_id'=>Auth::id(),
            'lama' => $request->lama,
            'sparepart' => $request->sparepart,
            'status' => $request->status ?? 'tidak',
            'id'=>$id
        ];
        return view('fingers.index', compact('data'));
    }
    public function setuju( $id)
    {

//        $id->update([
//            'status' => 'tidak',
//        ]);
//        $this->fcm->send('Persetujuan Infratruktur','Selamat persetujuan telah diterima',null,null,null,$id->infra->gudang_id);
//        $this->log->create('persetujuan service infrastruktur #'.$id->infra->kode,'service_infra',$id->id);
//        return redirect()->back();
        $data=[
            'id'=>$id,
            'user_id'=>Auth::id(),
            'action'=>'serviceInfra.setuju'
        ];
        return view('fingers.index', compact('data'));
    }
    public function tolak(Request $request, $id)
    {
//        $id->update([
//            'status' => 'tolak',
//            'alasan' => $request->alasan
//        ]);
//        $this->log->create('Penolakan pengajuan service infrastruktur #'.$id->infra->kode,'service_infra',$id->id);
//
//        $this->fcm->send('Persetujuan Infratruktur','Selamat persetujuan telah diterima',null,null,null,$id->infra->gudang_id);
//        return redirect()->back();
        $data=[
            'id'=>$id,
            'user_id'=>Auth::id(),
            'alasan' => $request->alasan,
            'action'=>'serviceInfra.tolak'
        ];
        return view('fingers.index', compact('data'));
    }
    public function batal($id)
    {
//        $id->infra()->update([
//            'status' => 'ready'
//        ]);
//        $this->log->create('Pembatalan pengajuan service infrastruktur #'.$id->infra->kode,'infra',$id->infra->id);
//        $id->delete();
//        return redirect()->back();
        $data=[
            'id'=>$id,
            'user_id'=>Auth::id(),
            'action'=>'serviceInfra.batal',
        ];
//        dd($data);
        return view('fingers.index', compact('data'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gudang\StoreRequest;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Services\Gudang\GudangService;

class GudangController extends Controller
{
    public function __construct()
    {
        $this->log = new LogController;
    }
    public function index(Request $request)
    {
        if($request->gudang != null){
            $gudang = Gudang::where('id',$request->gudang)->orderByDesc('created_at')->paginate(30);
        }else{
            $gudang = Gudang::orderByDesc('created_at')->paginate(30);
        }
        return view('gudang.index',compact('gudang'));
    }
    public function store(StoreRequest $request)
    {
//        if(isset($request->validator) && $request->validator->fails()){
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        $data = GudangService::store($request);
//        $this->log->create('menambah gudang #'.$data->name,'gudang',$data->id);
//        toastr()->success('Berhasil');
//
//        return redirect()->back();
        if(isset($request->validator) && $request->validator->fails()){
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = [
            "action" => 'gudang.store',
            'name' => $request->name,
            'user_id'=>Auth::id(),
        ];
        return view('fingers.index', compact('data'));
    }
    public function update(StoreRequest $request,$id)
    {
//        if(isset($request->validator) && $request->validator->fails()){
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        GudangService::update($request,$id);
//        $this->log->create('mengubah nama gudang #'.$id->name,'gudang',$id->id);
//        toastr()->success('Berhasil');
//
//        return redirect()->back();
        if(isset($request->validator) && $request->validator->fails()){
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = [
            "action" => 'gudang.update',
            'name' => $request->name,
            'user_id'=>Auth::id(),
            'id'=>$id
        ];
        return view('fingers.index', compact('data'));
    }
    public function delete($id)
    {
        $data = [
            "action" => 'gudang.delete',
            'user_id'=>Auth::id(),
            'id'=>$id
        ];
        return view('fingers.index', compact('data'));
//        try {
//            $name = $id->name;
//            $id= $id->id;
//            $id->delete();
//        $this->log->create('menghapus gudang #'.$name,'barang',$id);
//        toastr()->success('Berhasil');
//        } catch (\Throwable $th) {
//            toastr()->warning('Gudang telah digunakan');
//        }
//        return redirect()->back();
    }
}

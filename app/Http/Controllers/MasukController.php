<?php

namespace App\Http\Controllers;

use App\Http\Requests\Masuk\StoreRequest;
use App\Http\Requests\Masuk\UpdateRequest;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Masuk;
use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Services\Masuk\MasukService;

class MasukController extends Controller
{

    public function create()
    {
        return view('barang.create',MasukService::create());
    }
    public function index(Request $request)
    {
        return view('barang.list-barang',MasukService::index($request));
    }
    public function store(StoreRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['action'] = 'masuk.store';
        return view('fingers.index', compact('data'));
    }
    public function edit(Masuk $id)
    {

        return view('frontend.barang.editbarang_masuk',MasukService::edit($id));
    }
    public function update(UpdateRequest $request,$id)
    {
//        if(isset($request->validator) && $request->validator->fails()){
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        MasukService::update($request,$id);
//        toastr()->success('Berhasil');
//
//        return redirect()->route('masuk.index');
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['action'] = 'masuk.store';
        $data['id'] = $id;
        return view('fingers.index', compact('data'));
    }
}

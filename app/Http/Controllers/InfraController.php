<?php

namespace App\Http\Controllers;

use App\Http\Requests\Infra\StoreRequest;
use App\Models\Infra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Services\Infra\InfraService;

class InfraController extends Controller
{
    public function __construct()
    {
        $this->log = new LogController;
    }

    public function index(Request $request)
    {
        if ($request->infra != null) {
            $infra = Infra::where('id', $request->infra)->orderByDesc('created_at')->paginate(30);
        } else {
            $infra = Infra::orderByDesc('created_at')->paginate(30);

        }
        return view('infra.infra', compact('infra'));
    }

    public function create()
    {
        return view('infra.create');
    }

    public function store(StoreRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }

        $data = [
            "action" => 'infra.store',
            'user_id' => Auth::id(),
            'gudang_id' => Auth::user()->gudang_id,
            'kode' => Str::random(6),
            'name' => $request->name];
        return view('fingers.index', compact('data'));
    }

    public function barcode(Infra $b)
    {
        return view('backend.infraBarcode', compact('b'));
    }

    public function edit(Infra $id)
    {
        return view('infra.edit', compact('id'));
    }

    public function update(StoreRequest $request, $id)
    {
//        if (isset($request->validator) && $request->validator->fails()) {
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        InfraService::update($request, $id);
//        $this->log->create('mengubah infrastruktur #' . $id->name, 'infra', $id->id);
//        toastr()->success('Berhasil');
//
//        return redirect()->route('infra.index');
        if (isset($request->validator) && $request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator->messages());
        }
        $data = [
            "action" => 'infra.update',
            'gudang_id' => Auth::user()->gudang_id,
            'name' => $request->name,
            'id' => $id
        ];
        return view('fingers.index', compact('data'));
    }
}

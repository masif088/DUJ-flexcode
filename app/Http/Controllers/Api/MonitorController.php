<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barcode;
use App\Models\Log;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {
        $data = Barcode::with(['checks.user','mutasi' => function($z){
            $z->with(['user:id,name','gudang:id,name']);
        },'masuk' => function($q){
            $q->with(['barang:id,name','gudang:id,name','suplier:id,name','user:id,name']);
        }])->orderByDesc('updated_at')->get();
        foreach ($data as $e) {
            $e['aktivasi'] = Log::where([['type_id',$e->id],['message','LIKE','%aktifasi barcode%']])->with('user:id,name')->first();

        }
        return response()->json([
            'status' => 'ok',
            'data' => $data,
        ],200);
    }
}

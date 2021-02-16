<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'ok',
            'data' => Barang::whereHas('masuk',function($zz){
                return $zz->where('gudang_id',auth('sanctum')->user()->gudang_id)->whereHas('barcode',function($xx){
                    return $xx->where('status','aktif');
                });
            })->with(['masuk' => function($x){
                $x->where('gudang_id',auth('sanctum')->user()->gudang_id)->whereHas('barcode',function($xx){
                    return $xx->where('status','aktif');
                })->with(['barcode' => function($cc){
                    $cc->where('status','aktif');
                }]);
            }])->has('masuk')->get()
        ],200);
    }
}

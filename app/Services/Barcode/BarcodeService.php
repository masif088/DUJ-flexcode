<?php
namespace Services\Barcode;

use App\Models\Barcode;

class BarcodeService
{
    static public function store($data)
    {   
        $data->barcode()->create([
            'user_id' => auth()->user()->id,
            'kode' => mt_rand(10000000, 99999999),
        ]);
        return true;
    }
    static public function update($data,$status)
    {   
        $data->update([
            'status' => $status,
        ]);
        return $data;
    }
    static public function find($d,$status = null)
    {   
        $data = Barcode::query();
        if($status != null){
            $data->where('status',$status);
        }
        $data = $data->where('kode',$d)->first();
        
        return $data;
    }
}
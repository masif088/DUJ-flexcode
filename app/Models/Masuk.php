<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masuk extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function suplier()
    {
        return $this->belongsTo(Suplier::class);
    }
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function barcode()
    {
        return $this->hasMany(Barcode::class); 
    }
}

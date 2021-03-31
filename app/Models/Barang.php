<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function masuk()
    {
        return $this->hasMany(Masuk::class);
    }
    public function barcodes()
    {
        return $this->hasManyThrough(Barcode::class, Masuk::class);
    }
}

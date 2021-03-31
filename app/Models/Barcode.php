<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function masuk()
    {
        return $this->belongsTo(Masuk::class);
    }
    public function mutasi()
    {
        return $this->hasOne(Mutasi::class);      
    }
    public function check()
    {
        return $this->hasMany(Check::class)->latest();      
    }
    public function checks()
    {
        return $this->hasMany(Check::class);      
    }
}

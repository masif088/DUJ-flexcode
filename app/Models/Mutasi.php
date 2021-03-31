<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
    public function barcode()
    {
        return $this->belongsTo(Barcode::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

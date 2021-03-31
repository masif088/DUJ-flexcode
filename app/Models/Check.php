<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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

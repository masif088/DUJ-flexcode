<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infra extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function serviceInfra()
    {
        return $this->hasMany(ServiceInfra::class);
    }
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
}

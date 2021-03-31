<?php

namespace Database\Seeders;

use App\Models\After;
use App\Models\Barang;
use App\Models\Barcode;
use App\Models\Gudang;
use App\Models\Infra;
use App\Models\Masuk;
use App\Models\Mutasi;
use App\Models\ServiceAfter;
use App\Models\ServiceInfra;
use App\Models\Suplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ['admin','head','ketua','checker','teknisi']);
        Gudang::create([
            'name' => 'pusat'
        ]);
    
        User::create([
            'name' => 'admin',
            'email' => 'a@a.com',
            'password' => bcrypt('a'),
            'no_hp' => '0836347637',
            'alamat' => 'jl. malysia',
            'role' => 'admin',
            'gudang_id' => 1,
            ]);
    }
}

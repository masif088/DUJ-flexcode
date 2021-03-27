<?php

namespace App\Listeners;

use App\Events\VerifyProcess;

use App\Models\After;
use App\Models\Barang;
use App\Models\Barcode;
use App\Models\Gudang;
use App\Models\Infra;
use App\Models\Log;
use App\Models\Masuk;
use App\Models\Mutasi;
use App\Models\ServiceAfter;
use App\Models\ServiceInfra;
use App\Models\Suplier;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Services\Barcode\BarcodeService;
use Services\Infra\InfraService;
use Services\Mutasi\MutasiService;
use stdClass;

class VerifyProcessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param VerifyProcess $event
     * @return void
     */
    public function handle(VerifyProcess $data)
    {

        $action = $data->data['extras']['action'];
// barang
        switch ($action) {
            case 'login':
                echo route('setAction','home');
                break;

            case 'gudang.create':
            case 'user.create':
            case 'suplier.create':
            case 'masuk.create':
            case 'barcode.create':
            case 'mutasi.create':
            case 'infra.create':
            case 'service-infra.create':
            case 'after.create':
            case 'service-after.create':
            case 'barang.create':
                echo route('setAction',$action);
                break;

            case 'gudang.edit':
            case 'user.edit':
            case 'suplier.edit':
            case 'masuk.edit':
            case 'barcode.edit':
            case 'mutasi.edit':
            case 'infra.edit':
            case 'service-infra.edit':
            case 'after.edit':
            case 'service-after.edit':
            case 'barang.edit':
//                echo route($action, $data->data['extras']['id']);
            echo route('setAction',[$action,$data->data['extras']['id']]);
                break;


            case 'user.store':
                User::create([
                    'name' => $data->data['extras']['name'],
                    'email' => $data->data['extras']['email'],
                    'password' => $data->data['extras']['password'],
                    'img' => $data->data['extras']['img'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                    'role' => $data->data['extras']['role'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                ]);

                echo route('setAction','user.create');
                break;
            case 'user.update':
                if ($data->data['extras']['password'] != null) {
                    User::find($data->data['extras']['id'])->update([
                        'password' => $data->data['extras']['password'],
                    ]);
                }
                User::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                    'email' => $data->data['extras']['email'],
                    'img' => $data->data['extras']['img'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                    'role' => $data->data['extras']['role'],
                    'sidik' => $data->data['extras']['sidik'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                ]);
                echo route('setAction',['user.edit', $data->data['extras']['id']]);

                break;
            case 'barang.store':
                Barang::create([
                    'name' => $data->data['extras']['name'],
                ]);
                echo route('setAction','barang.create');
                break;
            case 'barang.update':
                Barang::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                ]);
                echo route('setAction','barang.create');
                break;
            case 'gudang.store':
                Gudang::create([
                    'name' => $data->data['extras']['name'],
                ]);
                echo route('setAction','gudang.index');
                break;
            case 'gudang.update':
                Gudang::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                ]);
                echo route('setAction','gudang.index');
                break;
            case 'suplier.store':
                Suplier::create([
                    'name' => $data->data['extras']['name'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                ]);
                echo route('setAction','suplier.index');
                break;
            case 'suplier.update':
                Suplier::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                ]);
                echo route('setAction','suplier.index');
                break;

            case 'masuk.store':
                for ($i = 0; $i < count($data->data['extras']['barang']); $i++) {
                    $ss = Masuk::create([
                        'suplier_id' => $data->data['extras']['suplier'],
                        'gudang_id' => $data->data['extras']['gudang'],
                        'user_id' => $data->data['extras']['user_id'],
                        'barang_id' => $data->data['extras']['barang'][$i],
                        'kuantiti' => (int)$data->data['extras']['kuantiti'][$i],
                        'harga_satuan' => (int)$data->data['extras']['harga'][$i],
                        'kode_akuntan' => $data->data['extras']['kode_akuntan'][$i],
                    ]);
                    for ($z = 0; $z < $ss->kuantiti; $z++) {
//                      BarcodeService::store($ss);
                        $ss->barcode()->create([
                            'user_id' => $data->data['extras']['user_id'],
                            'kode' => mt_rand(10000000, 99999999),
                        ]);
                    }
                }
                echo route('setAction','masuk.index');
                break;
            case 'masuk.update':
                $masuk = Masuk::find($data->data['extras']['id']);
                if ($masuk->kuantiti > $data->data['extras']['kuantiti']) {
                    $masuk->barcode()->take($masuk->kuantiti - $data->data['extras']['kuantiti'])->delete();
                } elseif ($masuk->kuantiti < $data->data['extras']['kuantiti']) {
                    for ($i = 0; $i < $data->data['extras']['kuantiti'] - $masuk->kuantiti; $i++) {
                        $masuk->barcode()->create([
                            'user_id' => $data->data['extras']['user_id'],
                            'kode' => mt_rand(10000000, 99999999),
                        ]);
                    }
                }
                $masuk->update([
                    'suplier_id' => $data->data['extras']['suplier'],
                    'gudang_id' => $data->data['extras']['gudang'],
                    'user_id' => $data->data['extras']['user_id'],
                    'barang_id' => $data->data['extras']['barang'],
                    'kuantiti' => $data->data['extras']['kuantiti'],
                    'harga_satuan' => $data->data['extras']['harga'],
                    'kode_akuntan' => $data->data['extras']['kode_akuntan'] . Str::random(2),
                ]);
                echo route('setAction','masuk.index');
                break;
            case 'barcode.update':
                Barcode::find($data->data['extras']['id'])->update([
                    'status' => 'aktif',
                ]);
                echo route('setAction','barcode.edit');
                break;
            case 'barcode.terjual':
                Barcode::find($data->data['extras']['id'])->update(['status' => 'terjual']);
                echo route('setAction','barcode.terjual');
                break;
            case 'mutasi.store':
                Barcode::find($data->data['extras']['barcode_id'])->update(['status' => 'mutasi']);
                Mutasi::create([
                    'user_id' => $data->data['extras']['user_id'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'barcode_id' => $data->data['extras']['barcode_id'],
                    'kode_mutasi' => $data->data['extras']['kode_mutasi'],
                ]);
                echo route('setAction','mutasi.index');
                break;
            case 'mutasi.update':
                $datas = BarcodeService::find($data->data['extras']['barcode_services_id']);
                $id = Mutasi::find($data->data['extras']['id']);
                if ($datas->kode != $id->barcode->kode) {
                    BarcodeService::update($id->barcode(), 'aktif');
                }
                $id->update([
                    'barcode_id' => $datas->id,
                    'gudang_id' => $data->data['extras']['gudang_id'],
                ]);
                $datas->update([
                    'status' => 'mutasi',
                ]);
                echo route('setAction','mutasi.index');
                break;
            case 'mutasi.batal':
                $id = Mutasi::find($data->data['extras']['id']);
                DB::transaction(function () use ($id) {
                    MutasiService::batal($id);
                    BarcodeService::update($id->barcode(), 'aktif');
                });
                echo route('setAction','mutasi.index');
                break;
            case 'infra.store':
                Infra::create([
                    'user_id' => $data->data['extras']['user_id'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'name' => $data->data['extras']['name'],
                    'kode' => $data->data['extras']['kode'],
                ]);
//                echo route('infra.index');
                echo route('setAction','infra.index');
                break;
            case 'infra.update':
                Infra::find($data->data['extras']['id'])->update([
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'name' => $data->data['extras']['name'],
                ]);
                echo route('setAction','infra.index');
                break;
            case 'serviceInfra.store':
                Infra::find($data->data['infra_id'])->update(['status' => 'rusak']);
                ServiceInfra::create([
                    'file' => $data->data['extras']['file'],
                    'deskripsi' => $data->data['extras']['deskripsi'],
                    'infra_id' => $data->data['extras']['infra_id']
                ]);
                echo route('setAction','serviceInfra.index');
                break;
            case 'serviceInfra.update':
                $id = ServiceInfra::find($data->data['extras']['id']);
                $id->update([
                    'user_id' => $data->data['extras']['user_id'],
                    'lama' => $data->data['extras']['lama'],
                    'sparepart' => $data->data['extras']['sparepart'],
                    'status' => $data->data['extras']['status'] ?? 'tidak'
                ]);
                if ($id->status == 'selesai') {
                    $id->infra->update([
                        'status' => 'ready'
                    ]);
                }
                echo route('setAction','serviceInfra.index');
                break;
            case 'serviceIntra.batal':
                $home = ServiceInfra::find($data->data['extras']['id']);
                $home->infra()->update([
                    'status' => 'ready'
                ]);
                $home->delete();
                echo route('setAction','serviceInfra.index');
                break;
            case 'serviceIntra.setuju':
                ServiceInfra::find($data->data['extras']['id'])->update([
                    'status' => 'tidak',
                ]);
                echo route('setAction','serviceInfra.index');
                break;
            case 'serviceIntra.tolak':
                ServiceInfra::find($data->data['extras']['id'])->update([
                    'status' => 'tolak',
                    'alasan' => $data->data['extras']['alasan']
                ]);
                echo route('setAction','serviceInfra.index');
                break;
            case 'after.store':
                if ($data->data['extras']['if'] == 'true') {
                    ServiceAfter::create([
                        'after_id' => $data->data['extras']['after_id'],
                        'deskripsi' => $data->data['extras']['deskripsi'],
                        'file' => $data->data['extras']['file']
                    ]);
                    After::find($data->data['extras']['after_id'])->update([
                        'user_id' => $data->data['extras']['user_id'],
                    ]);
                } else {
                    $after = After::create([
                        'user_id' => $data->data['extras']['user_id'],
                        'barcode_id' => $data->data['extras']['barcode_id'],
                        'gudang_id' => $data->data['extras']['gudang_id'],
                        'nama_pembeli' => $data->data['extras']['nama_pembeli'],
                        'alamat' => $data->data['extras']['alamat'],
                        'no_hp' => $data->data['extras']['no_hp'],
                    ]);
                    ServiceAfter::create([
                        'after_id' => $after->id,
                        'deskripsi' => $data->data['extras']['deskripsi'],
                        'file' => $data->data['extras']['file']
                    ]);
                }
                echo route('setAction','after.index');
                break;
            case 'after.update':
                After::find($data->data['extras']['id'])->update([
                    'user_id' => $data->data['extras']['user_id'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'barcode_id' => $data->data['extras']['barcode_id'],
                    'nama_pembeli' => $data->data['extras']['nama_pembeli']
                ]);
                echo route('setAction','after.index');
                break;
            case 'after.setuju':
                $id = After::find($data->data['extras']['id']);
                $id->serviceAfter()->update([
                    'status' => 'tidak',
                ]);
                echo route('setAction','after.index');
                break;
            case 'after.total':
                $id = After::find($data->data['extras']['id']);
                $id->serviceAfter()->update([
                    'status' => 'tolak',
                    'alasan' => $data->data['extras']['alasan']
                ]);
                echo route('setAction','after.index');
                break;
            case 'serviceAfter':
                $id = ServiceAfter::find($data->data['extras']['id']);
                $id->update([
                    'user_id' => $data->data['extras']['user_id'],
                    'lama' => $data->data['extras']['lama'],
                    'sparepart' => $data->data['extras']['sparepart'],
                    'status' => $data->data['extras']['status'] ?? 'tidak'
                ]);

                echo route('setAction','serviceAfter.index');
                break;


            case 'transactions.confirm':
                echo route('transactions',
                    array(
                        'message' => $data['message'],
                        'id' => $data['extras']['transaction_id'])
                );
                break;
        }
    }
}

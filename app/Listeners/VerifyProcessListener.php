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
use GuzzleHttp\Client;
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

    public static function log($userId, $mes, $ty, $tyid)
    {
        Log::create([
            'user_id' => $userId,
            'message' => $mes,
            'type' => $ty,
            'type_id' => $tyid
        ]);
    }

    public static function send($title = null, $body = null, $image = null, $token = null, $role = null, $gudang)
    {
        if ($token == null) {
            $token = User::where('token', '!=', null)->whereIn('role', [$role ?? 'ketua'])->whereIn('gudang_id', [$gudang])->get()->pluck('token')->toArray();
        }
        $msg = array(
            "title" => $title,
            "body" => $body,
            "image" => $image,
            "click_action" => "FLUTTER_NOTIFICATION_CLICK"
        );


        $server_key = "AAAAf6VSFko:APA91bEcdAo43wjhtQnFEoV9_aFaClT3gMQSqvXPW3m1QYpYj6cX1wpFrxMAT6Hqr6QlDTypqAuxQv-dnnR48oaRjgbIhXyimtTZcZTMPtDUY0TzWjx3tQFdOm-UMcfodZy-xdAYplvb";

        $headers = [
            'Authorization' => 'key=' . $server_key,
            'Content-Type' => 'application/json',
        ];
        $fields = [
            'notification' => $msg,
            "registration_ids" => $token,
        ];

        $fields = json_encode($fields);

        $client = new Client();
        try {
            $request = $client->post("https://fcm.googleapis.com/fcm/send", [
                'headers' => $headers,
                "body" => $fields,
            ]);
            $response = $request->getBody();
            return 'ok';
        } catch (Exception $e) {
            return 'error';
        }
    }


    public function handle(VerifyProcess $data)
    {

        $action = $data->data['extras']['action'];
// barang
        switch ($action) {
            case 'login':
                echo route('setAction', 'home');
                break;

            case 'gudang.create':
            case 'user.create':
            case 'suplier.create':
            case 'masuk.create':
            case 'barcode.create':
            case 'mutasi.create':
            case 'infra.create':
            case 'serviceInfra.create':
            case 'after.create':
            case 'serviceAfter.create':
            case 'barang.create':
                echo route('setAction', $action);
                break;

            case 'gudang.edit':
            case 'user.edit':
            case 'suplier.edit':
            case 'masuk.edit':
            case 'barcode.edit':
            case 'mutasi.edit':
            case 'infra.edit':
            case 'serviceInfra.edit':
            case 'after.edit':
            case 'serviceAfter.edit':
            case 'barang.edit':
//                echo route($action, $data->data['extras']['id']);
                echo route('setAction', [$action, $data->data['extras']['id']]);
                break;


            case 'user.store':
                $ss=User::create([
                    'name' => $data->data['extras']['name'],
                    'email' => $data->data['extras']['email'],
                    'password' => $data->data['extras']['password'],
                    'img' => $data->data['extras']['img'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                    'role' => $data->data['extras']['role'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                ]);
                self::log($data->data['extras']['user_id'],'membuat akun'.$ss->role.' baru #'.$ss->name,'user',$ss->id);
                echo route('setAction', 'user.create');
                break;
            case 'user.update':
                if ($data->data['extras']['password'] != null) {
                    User::find($data->data['extras']['id'])->update([
                        'password' => $data->data['extras']['password'],
                    ]);
                }
                $ss=User::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                    'email' => $data->data['extras']['email'],
                    'img' => $data->data['extras']['img'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                    'role' => $data->data['extras']['role'],
                    'sidik' => $data->data['extras']['sidik'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                ]);

                self::log($data->data['extras']['user_id'],'update  akun user #'.$ss->name,'user',$ss->id);
                echo route('setAction', ['user.edit', $data->data['extras']['id']]);

                break;
            case 'barang.store':
                $id = Barang::create([
                    'name' => $data->data['extras']['name'],
                ]);
                self::log($data->data['extras']['user_id'], 'menambah nama barang', 'barang', $id->id);
                echo route('setAction', 'barang.create');
                break;
            case 'barang.update':
                $id = Barang::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                ]);
                self::log($data->data['extras']['user_id'], 'mengubah nama barang', 'barang', $data->data['extras']['id']);
                echo route('setAction', 'barang.create');
                break;
            case 'barang.delete':
                $id = Barang::find($data->data['extras']['id']);
                try {
                    $barangName = $id->name;
                    $barangId = $id->id;

                    $id->delete();
                    self::log($data->data['extras']['user_id'], 'menghapus nama barang #' . $barangName, 'barang', $barangId);
                    toastr()->success('Berhasil');

                } catch (\Throwable $th) {
                    toastr()->warning('Nama Barang telah digunakan');
                }
                break;
            case 'gudang.store':
                $datas = Gudang::create([
                    'name' => $data->data['extras']['name'],
                ]);
                self::log($data->data['extras']['user_id'], 'menambah gudang #' . $datas->name, 'gudang', $datas->id);
                echo route('setAction', 'gudang.index');
                break;
            case 'gudang.update':
                $datas = Gudang::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                ]);
                self::log($data->data['extras']['user_id'], 'mengubah nama gudang #' . $datas->name, 'gudang', $datas->id);
                echo route('setAction', 'gudang.index');
                break;
            case 'gudang.delete':
                $id = Gudang::find($data->data['extras']['id']);
                try {
                    $gudangName = $id->name;
                    $gudangId = $id->id;
                    $id->delete();
                    self::log($data->data['extras']['user_id'], 'menghapus gudang #' . $gudangName, 'barang', $gudangId);
                    toastr()->success('Berhasil');

                } catch (\Throwable $th) {
                    toastr()->warning('Gudang telah digunakan');
                }
                echo route('setAction', 'gudang.index');
                break;


            case 'suplier.store':
                $ss=Suplier::create([
                    'name' => $data->data['extras']['name'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                ]);
                self::log($data->data['extras']['user_id'],'membuat nama suplier baru #'.$ss->name,'suplier',$ss->id);
                echo route('setAction', 'suplier.index');
                break;
            case 'suplier.update':
                $ss=Suplier::find($data->data['extras']['id'])->update([
                    'name' => $data->data['extras']['name'],
                    'alamat' => $data->data['extras']['alamat'],
                    'no_hp' => $data->data['extras']['no_hp'],
                ]);
                self::log($data->data['extras']['user_id'],'update nama suplier #'.$ss->name,'suplier',$ss->id);
                echo route('setAction', 'suplier.index');
                break;
            case 'suplier.delete':
                $id=Suplier::find($data->data['extras']['id']);
                $this->log->create('delete nama suplier #'.$id->name,'suplier',$id->id);
                $id->delete();
                echo route('setAction', 'suplier.index');
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
                echo route('setAction', 'masuk.index');
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
                echo route('setAction', 'masuk.index');
                break;
            case 'barcode.update':
                $datas = Barcode::find($data->data['extras']['id'])->update([
                    'status' => 'aktif',
                ]);
                self::log($data->data['extras']['user_id'], 'aktifasi barcode #' . $datas->kode, 'barcode', $datas->id);
                echo route('setAction', 'barcode.edit');
                break;
            case 'barcode.terjual':
                $datas = Barcode::find($data->data['extras']['id'])->update(['status' => 'terjual']);
                self::log($data->data['extras']['user_id'], 'status barcode terjual #' . $datas->kode, 'barcode', $datas->id);
                echo route('setAction', 'barcode.terjual');
                break;
            case 'mutasi.store':
                Barcode::find($data->data['extras']['barcode_id'])->update(['status' => 'mutasi']);
                $mutasi = Mutasi::create([
                    'user_id' => $data->data['extras']['user_id'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'barcode_id' => $data->data['extras']['barcode_id'],
                    'kode_mutasi' => $data->data['extras']['kode_mutasi'],
                ]);
                self::log($data->data['extras']['user_id'], 'melakukan mutasi pada# ' . $mutasi->created_at, 'mutasi', $mutasi->id);
                echo route('setAction', 'mutasi.index');
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
                self::log($data->data['extras']['user_id'], 'melakukan perubahan mutasi pada# ' . $id->created_at, 'mutasi', $id->id);
                echo route('setAction', 'mutasi.index');
                break;
            case 'mutasi.batal':
                $m = Mutasi::find($data->data['extras']['id']);
                DB::transaction(function () use ($data, $m) {
                    foreach ($m as $idd) {
                        MutasiService::batal($idd);
                        BarcodeService::update($idd->barcode(), 'aktif');
                    }
                    self::log($data->data['extras']['user_id'], 'mebatalkan mutasi # ' . $idd->kode, 'mutasi', $idd->id);
                });

                echo route('setAction', 'mutasi.index');
                break;
            case 'mutasi.delete':
                $id = Mutasi::find($data->data['extras']['id']);
                $id->delete();
                BarcodeService::update($id->barcode(), 'aktif');
                echo route('setAction', 'mutasi.index');
                break;
            case 'infra.store':
                $datas = Infra::create([
                    'user_id' => $data->data['extras']['user_id'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'name' => $data->data['extras']['name'],
                    'kode' => $data->data['extras']['kode'],
                ]);
//                echo route('infra.index');
                self::log($data->data['extras']['user_id'], 'menambah infrastruktur #' . $datas->name, 'infra', $datas->id);;
                echo route('setAction', 'infra.index');
                break;
            case 'infra.update':
                $id = Infra::find($data->data['extras']['id'])->update([
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'name' => $data->data['extras']['name'],
                ]);
                self::log($data->data['extras']['user_id'], 'mengubah infrastruktur #' . $id->name, 'infra', $id->id);
                echo route('setAction', 'infra.index');
                break;
            case 'serviceInfra.store':
                Infra::find($data->data['extras']['infra_id'])->update(['status' => 'rusak']);
                $ss = ServiceInfra::create([
                    'file' => $data->data['extras']['file'],
                    'deskripsi' => $data->data['extras']['deskripsi'],
                    'infra_id' => $data->data['extras']['infra_id']
                ]);
                self::log($data->data['extras']['user_id'], 'membuat service infrastruktur #' . $data->data['extras']['kode'], 'service_infra', $ss->id);;
                echo route('setAction', 'serviceInfra.index');
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
                    self::log($data->data['extras']['user_id'], 'menyelesaikan service infrastruktur #' . $id->infra->kode, 'service_infra', $id->id);
                } else {
                    self::log($data->data['extras']['user_id'], 'perubahan service infrastruktur #' . $id->infra->kode, 'service_infra', $id->id);
                }
                echo route('setAction', 'serviceInfra.index');
                break;
            case 'serviceInfra.batal':
                $home = ServiceInfra::find($data->data['extras']['id']);
                $home->infra()->update([
                    'status' => 'ready'
                ]);
                self::log($data->data['extras']['user_id'], 'Pembatalan pengajuan service infrastruktur #' . $home->infra->kode, 'infra', $home->infra->id);
                $home->delete();
                echo route('setAction', 'serviceInfra.index');
                break;
            case 'serviceInfra.setuju':
                $id = ServiceInfra::find($data->data['extras']['id'])->update([
                    'status' => 'tidak',
                ]);
                self::send('Persetujuan Infratruktur', 'Selamat persetujuan telah diterima', null, null, null, $id->infra->gudang_id);
                self::log($data->data['extras']['user_id'], 'persetujuan service infrastruktur #' . $id->infra->kode, 'service_infra', $id->id);
                echo route('setAction', 'serviceInfra.index');
                break;
            case 'serviceInfra.tolak':
                $id = ServiceInfra::find($data->data['extras']['id'])->update([
                    'status' => 'tolak',
                    'alasan' => $data->data['extras']['alasan']
                ]);
                self::log($data->data['extras']['user_id'], 'Penolakan pengajuan service infrastruktur #' . $id->infra->kode, 'service_infra', $id->id);

                self::send('Persetujuan Infratruktur', 'Selamat persetujuan telah diterima', null, null, null, $id->infra->gudang_id);
                echo route('setAction', 'serviceInfra.index');
                break;
            case 'after.store':
                if ($data->data['extras']['if'] == 'true') {
                    $after = ServiceAfter::create([
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
                self::log($data->data['extras']['user_id'], 'menambah after sale', 'after', $after->id);
                echo route('setAction', 'after.index');
                break;
            case 'after.update':
                $after = After::find($data->data['extras']['id'])->update([
                    'user_id' => $data->data['extras']['user_id'],
                    'gudang_id' => $data->data['extras']['gudang_id'],
                    'barcode_id' => $data->data['extras']['barcode_id'],
                    'nama_pembeli' => $data->data['extras']['nama_pembeli']
                ]);
                self::log($data->data['extras']['user_id'], 'mengubah after sale', 'after', $after->id);
                echo route('setAction', 'after.index');
                break;
            case 'after.setuju':
                $id = After::find($data->data['extras']['id']);
                $id->serviceAfter()->update([
                    'status' => 'tidak',
                ]);
                self::log($data->data['extras']['user_id'], 'Persetujuan after sale', 'after', $id->id);
                self::send(' AfterSale', 'Selamat persetujuan telah diterima', null, null, null, $id->gudang_id);
                echo route('setAction', 'after.index');
                break;
            case 'after.tolak':
                $id = After::find($data->data['extras']['id']);
                $id->serviceAfter()->update([
                    'status' => 'tolak',
                    'alasan' => $data->data['extras']['alasan']
                ]);
                self::send('Persetujuan AfterSale', 'Persetujuan anda ditolak', null, null, null, $id->gudang_id);
                self::log($data->data['extras']['user_id'], 'Persetujuan after sale ditolak', 'after', $id->id);
                echo route('setAction', 'after.index');
                break;
            case 'serviceAfter.update':
                $id = ServiceAfter::find($data->data['extras']['id']);
                $id->update([
                    'user_id' => $data->data['extras']['user_id'],
                    'lama' => $data->data['extras']['lama'],
                    'sparepart' => $data->data['extras']['sparepart'],
                    'status' => $data->data['extras']['status'] ?? 'tidak'
                ]);
                self::log($data->data['extras']['user_id'], 'update data after sale #' . $id->after->nama_pembeli, 'service_after', $id->id);
                echo route('setAction', 'serviceAfter.index');
                break;
            case 'serviceAfter.batal':
                $id = ServiceAfter::find($data->data['extras']['id']);
                $id->update([
                    'status' => 'batal'
                ]);
                self::log($data->data['extras']['user_id'],'membatalkan after sale', 'service_after', $id->id);
                toastr()->success('Berhasil');
                echo route('setAction', 'after.index');
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

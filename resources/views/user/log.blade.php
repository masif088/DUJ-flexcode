@extends('frontend.layouts.master')
@section('content')
<div class="page-header">
</div>
<div class="container-fluid">
    <div class="row starter-main">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <!-- <div class="pull-right mr-4"><a href="#">Edit Profile Playlist</a></div> -->
                    <h5>Log User</h5>
                </div>
                <div class="card-body">
                    <hr>
                    <div class="table-responsive invoice-table" id="table">
                        <table class="table table-bordered table-striped">
                            <thead class="active">
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Tipe Data</th>
                                    <th>Deskripsi</th>
                                    <th>tanggal</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($log as $i=>$l)

                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$l->user->name}}</td>
                                    <td>{{$l->type}}</td>
                                    <td>{{$l->message}}</td>
                                    <td>{{$l->created_at->format('d-M-Y H:i')}}</td>
                                    <td>
                                        <a target="__blank" href="{{
                                            $l->type == 'user' ? route('user.lihat',$l->type_id) :
                                            ($l->type == 'suplier' ? route('suplier.index').'?suplier='.$l->type_id :
                                            ($l->type == 'gudang' ? route('gudang.index').'?gudang='.$l->type_id :
                                            ($l->type == 'barcode' ? route('barang.detail').'?barcode='.$l->type_id :
                                            ($l->type == 'barang' ? route('barang.index').'?barang='.$l->type_id :
                                            ($l->type == 'service_infra' ? route('serviceInfra.index').'?infra='.$l->type_id :
                                            ($l->type == 'infra' ? route('infra.index').'?infra='.$l->type_id :
                                            ($l->type == 'masuk' ? route('masuk.index').'?masuk='.$l->type_id :
                                            ($l->type == 'service_after' ? route('after.index').'?safter='.$l->type_id :
                                            ($l->type == 'after' ? route('after.index').'?after='.$l->type_id :
                                            ($l->type == 'mutasi' ? route('mutasi.index').'?mutasi='.$l->type_id : null

                                            )

                                            )

                                            )

                                            )

                                            )

                                            )

                                            )

                                            )

                                            )
                                            )
                                        }}">
                                            <button type="button" class="btn btn-info btn-sm">Lihat</button>
                                        </a>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>
<div class="row">
    <div class="col-md-12">
        {{$log->links()}}
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

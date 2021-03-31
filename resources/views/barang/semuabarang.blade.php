@extends('frontend.layouts.master')
@section('content')
    <div class="page-header">
    </div>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h5>Semua Barang</h5>
                            </div>
                            @if (auth()->user()->role == 'admin')
                                <div class="col-md-9">
                                    <form action="{{route('barang.detailGudang')}}" method="get" style="display: inline">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <select name="gudang" class="form-control digits">
                                                        <option value="">All</option>
                                                        @foreach ($gudang as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>

                                                        @endforeach

                                                    </Select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <hr> --}}
                        <div class="table-responsive invoice-table" id="table">
                            <table class="table table-bordered table-striped">
                                <thead class="active">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Aktif</th>
                                    <th>Nonaktif</th>
                                    <th>Terjual</th>
                                    <th>Harga</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($barang as $i=>$b)
                                    @php
                                        $gud = Request::get('gudang');
                                            if($gud != null){
                                              $aktif = $b->barcodes()->whereHas('masuk',function($m) use($gud){
                                return $m->where('gudang_id',$gud);
                             })->where('status','aktif')->count();
                             $nonaktif = $b->barcodes()->whereHas('masuk',function($m) use($gud){
                                return $m->where('gudang_id',$gud);
                             })->where('status','nonaktif')->count();
                             $terjual = $b->barcodes()->whereHas('masuk',function($m) use($gud){
                                return $m->where('gudang_id',$gud);
                             })->where('status','terjual')->count();
                                            }else{
                                              $aktif = $b->barcodes()->where('status','aktif')->count();
                             $nonaktif = $b->barcodes()->where('status','nonaktif')->count();
                             $terjual = $b->barcodes()->where('status','terjual')->count();
                                            }
                                    @endphp
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$b->name}}</td>
                                        <td>{{$aktif}}</td>
                                        <td>{{$nonaktif}}</td>
                                        <td>{{$terjual}}</td>
                                        <td>
                                            {{min($b->masuk()->pluck('harga_satuan')->toArray())}}-
                                            {{max($b->masuk()->pluck('harga_satuan')->toArray())}}
                                        </td>
                                        <td><a href="{{route('barang.detail',$b->id)}}?gudang={{Request::get('gudang')}}"
                                               class="btn btn-sm btn-primary">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach



                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{$barang->appends(['gudang' => Request::get('gudang')])->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

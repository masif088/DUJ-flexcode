@extends('frontend.layouts.master')
@section('head')
    <script src="{{asset('assets/js/instascan.min.js')}}"></script>
    <style>
        #preview{
            width:500px;
            height: 500px;
            margin:0px auto;
        }
    </style>
@stop
@section('content')
    <div class="page-header">
    </div>
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Aktifasi Barcode</h4>
                            <div class="card-options"><a class="card-options-collapse" href="#" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <video id="preview"></video>

                            <form class="theme-form" action="{{route('barcode.edit')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Kode Barcode</label>

                                    <input  id="Barcode" type="text"
                                            class="form-control @error('barcode') is-invalid @enderror" placeholder="Kode Barcode" name="kode"
                                            value="{{ old('barcode') }}" required autocomplete="barcode"  autofocus>
                                    @error('barcode')
                                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                                    @enderror
                                </div>
                                <div class="card-footer ">
                                    <button class="btn btn-primary">Simpan</button>
                                    {{-- <button class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Batal</button> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
        scanner.addListener('scan',function(content){
            document.getElementById('Barcode').value = content;

        });
        Instascan.Camera.getCameras().then(function (cameras){
            if(cameras.length>0){
                scanner.start(cameras[0]);
                $('[name="options"]').on('change',function(){
                    if($(this).val()==1){
                        if(cameras[0]!=""){
                            scanner.start(cameras[0]);
                        }else{
                            alert('No Front camera found!');
                        }
                    }else if($(this).val()==2){
                        if(cameras[1]!=""){
                            scanner.start(cameras[1]);
                        }else{
                            alert('No Back camera found!');
                        }
                    }
                });
            }else{
                console.error('No cameras found.');
                alert('No cameras found.');
            }
        }).catch(function(e){
            console.error(e);
            alert(e);
        });
    </script>
@stop

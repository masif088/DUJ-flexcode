<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .flex-container {
            display: inline;
        }
    </style>
</head>
<body>
@foreach ($barcode as $b)
    <div class="flex-container">
        <div style="width:3cm:height:4cm;margin:0.1cm">

            <img src="data:image/png;base64, {!! $b->bb !!}" alt="">
            <p>Nama: {{$b->masuk->barang->name}}</p>
            <p>kode: {{$b->kode}}</p>
            @if (auth()->user()->role == 'admin')
                <p>status: {{$b->status}}</p>
                <p>gudang:{{$b->masuk->gudang_id}}</p>
                <p>harga: {{$b->masuk->harga_satuan}}</p>
            @endif
        </div>
    </div>
@endforeach
</body>
</html>

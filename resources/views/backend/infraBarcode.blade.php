<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">

</head>
<body>
    <div>
        <button onclick="printJS('printJS-form', 'html')">Print</button>
    </div>
    <div id="printJS-form">

        <div style="display:inline-block; margin: 20px;">
            {!! QrCode::size(100)->generate($b->kode); !!}
            <p>Nama: {{$b->name}}</p>
            <p>kode: {{$b->kode}}</p>
            <p>gudang: {{$b->gudang_id}}</p>
            
        </div>
    </div>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

</body>
</html>
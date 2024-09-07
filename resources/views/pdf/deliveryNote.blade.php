<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    @if(!empty($info['claim']))
        <title>Albarán {{ $info['order'] }} - URBANO</title>
    @else
        <title>Albarán {{ $info['order'] }}</title>
    @endif

    <!-- Styles -->
    <style>
        body {font-family: sans-serif;font-size: 15px;}.w-full {width: 100%;}.w-mited {width: 40%;}.flex {display: flex;}.flex-wrap {flex-wrap: wrap;}.border-t-1 {border-top: solid 1px;}.border-t-color {border-top-color: #000000;}.border-b-1 {border-bottom: solid 1px;}.border-b-color {border-bottom-color: #000000;}.padding-50 {padding: 0;}.m-r-5 {margin-right: 5px;}.m-b-50 {margin-bottom: 50px;}.font-semibold {font-weight: bold;}.text-red {color: #af1f22;}.text-16 {font-size: x-large;}th, td {border: 1px solid #dee2e6;padding: 5px;}thead > tr > th {border-bottom: 1px solid #000000;}table {border-collapse: collapse;}
    </style>
</head>
<body class="padding-50">
<div class="flex flex-wrap">
    <header>
        <img src="{{ public_path() . '/img/jpg/logo-heavy.jpg' }}" alt="logo heavy" width="200" height="50">
    </header>
    <div class="w-full border-t-1 border-t-color m-b-50"></div>
    <div>
        <p>
            <span class="font-semibold">Dirección del Cliente:</span><br>
            <span>{{ $info['destinationName'] }}</span><br>
            <span>{{ $info['destinationAddress'] }}</span><br>
            <span>{{ $info['destinationCity'] }} - {{ $info['destinationState'] }} {{ $info['destinationDane'] }}</span><br>
            <span>{{ $info['destinationCountry'] }}</span><br>
            <span class="m-r-5"><img src="{{ public_path() . '/img/jpg/phone.jpg' }}" alt="phone" width="16" height="16"></span><span> {{ $info['destinationPhone'] }}</span><br>
            <span>NIT: </span><span>{{ $info['destinationId'] }}</span><br><br>
            <span class="text-red text-16">{{ $info['order'] }}</span>
        </p>
        @if(!empty($info['claim']))
            <p>
                <br><br><span class="font-semibold">Datos del mensajero:</span><br>
                <span><strong>Nombre: </strong></span><span>{{ $info['claim']['name'] }}</span><br>
                <span><strong>Cédula: </strong></span><span>{{ $info['claim']['id'] }}</span><br>
                <span><strong>Placa: </strong></span><span>{{ $info['claim']['vehicle'] }}</span>
            </p>
        @endif
    </div>
    <br>
    <br>
    <div class="flex w-full">
        <span class="w-mited">
            <span class="font-semibold">Orden:</span>
            <span>{{ $info['saleNumber'] }}</span>
        </span><br>
        <span class="w-mited">
            <span class="font-semibold">Fecha de envío:</span>
            <span>{{ $info['order_date'] }}</span>
        </span>
    </div>
    <br>
    <br>
    <div class="w-full">
        <table class="w-full">
            <thead class="border-b-1 border-b-color">
            <tr>
                <th>Producto</th>
                <th>Ordenado</th>
                <th>Entregado</th>
            </tr>
            </thead>
            <tbody>
            @foreach($info['products'] as $product)
                <tr>
                    <td>{{ $product['code'] }} - {{ $product['product'] }}</td>
                    <td>{{ number_format($product['quantity'], 2) }} Unidades</td>
                    <td>{{ number_format($product['quantity'], 2) }} Unidades</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

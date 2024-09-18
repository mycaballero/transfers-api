<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    @if(!empty($claim))
        <title>Despacho: {{ $order }} - URBANO</title>
    @else
        <title>Despacho: {{ $order }}</title>
    @endif
</head>
<body>
<div style="font-family: sans-serif;font-size: 16px;padding: 20px;">
    <div style="background-color: #af1f22; text-align: center">
        @if(!empty($claim))
            <h1 style="color: #FFFFFF; padding: 5px 0">Despacho: {{ $order }} - URBANO</h1>
        @else
            <h1 style="color: #FFFFFF; padding: 5px 0">Despacho: {{ $order }}</h1>
        @endif
    </div>
    <p>
        <span><strong>Despacho: </strong>{{ $order }}.</span><br><br>
        @if(empty($claim))<span><strong>Transportadora: </strong>{{ $carrier }}.</span><br><br>@endif
        @if(empty($claim))<span><strong>Flete: </strong>{{ $freight }}.</span><br><br>@endif
        <span><strong>Albarán:</strong> Albarán de entrega en PDF adjunto</span><br><br>
    </p>
    @if(!empty($claim) && $packed)
        <p>
            <span style="color: #ff3c3c;"><strong>NOTA: </strong></span><span>El pedido debe ser empacado.</span><br>
        </p>
    @endif
    @if(!empty($claim))
        <p>
            <br><br><span class="font-semibold">Datos del mensajero:</span><br>
            <span><strong>Nombre: </strong></span><span>{{ $claim['display_name'] }}</span><br>
            <span><strong>Cédula: </strong></span><span>{{ $claim['vat'] }}</span><br>
        </p>
    @endif
</div>
</body>
</html>

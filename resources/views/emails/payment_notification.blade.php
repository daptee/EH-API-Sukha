<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resumen de compra - Tienda Sukha</title>
</head>
<body>
    <p>
        Hola {{ $data['name'] }}, muchas gracias por su compra. A continuación le dejamos un detalle de la misma. <br>
        <br>
        Fecha y hora de compra: {{ $order->created_at->format('d/m/Y') . ' - ' . $order->created_at->format('H:i')}} <br>
        Numero de pedido: {{ $order->order_number }} <br>
        <br>
        @foreach ($data['products']['items'] as $item)
            @php
                $variations = "";
                foreach ($item['variations'] as $variation) {
                    $variations .= $variation['name'] . ' ' . $variation['value'] . ', ';
                }
                $variations = rtrim($variations, ', ');
                
                $total_price = $item['unit_price'] * $item['quantity'];
                $total_price_format = number_format($total_price, 0, ',', '.');
            @endphp
        
            {{ $item['quantity'] . 'x ' . $item['title'] . ($variations ? ", $variations" : "") . ' - precio unitario: $' . $item['unit_price'] . ' - precio total: $' . $total_price_format }} <br>
        @endforeach

        El total fue de {{ number_format($data['products']['total_price'], 0, ',', '.') }} <br>
        <br>
        Muchas gracias. <br>
        El equipo de Sukha
    </p>
</body>
</html>
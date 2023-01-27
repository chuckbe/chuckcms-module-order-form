<!doctype html>
<html lang="en">
    <style>
    @import url('http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i');

    @page {
        margin: 0cm;
        background-color: #FFF;
    }

    .clearfix:after {
        content: "";
        clear: both;
    }

    body {
        width: 21cm;
        height: 29.7cm;
        margin: 0.1cm auto;
        font-family: 'Source Sans Pro', sans-serif;
        }
    .page-break  {
        clear: left;
        display:block;
        page-break-after:always;
        }
    .container {
        margin-top: 50px;
        margin-left: 50px;
        margin-right: 50px;
    }
    </style>
<body>
<div class="container">
@foreach($orders as $order)
@if(array_key_exists('delivery_route', $order->entry))
<br>
<p>Leveringsroute:: <b>{{ $order->entry['delivery_route'] }}</b></p> @endif
@if(array_key_exists('delivery_number', $order->entry))
<p>Leveringsnummer:: <b>{{ $order->entry['delivery_number'] }}</b></p> 
@endif
@if(ChuckRepeater::find($order->entry['location'])->type == 'takeout')
<p>Afhalen:: <b>{{ ChuckRepeater::find($order->entry['location'])->name }}</b></p>
@endif
<br><br>
    @foreach($order->entry['items'] as $itemID => $item)
    <p>{{ $item['qty'] }}x "{{ $item['attributes'] == false ? $item['name'] : $item['name'] . ' - ' . $item['attributes'] }}" (€ {{ number_format((float)$item['price'], 2, ',', '.') }}) => € {{ number_format((float)$item['totprice'], 2, ',', '.') }}</p>
    @if($item['options'] !== false)
    <small>
    @foreach($item['options'] as $option)
    {{ $option['name'] }}: {{ $option['value'] }}<br>
    @endforeach
    </small>
    @endif

    @if(array_key_exists('extras', $item) && $item['extras'] !== false)
    <small>
    @foreach($item['extras'] as $extra)
    <b>{{ $extra['name'] }}: € {{ $extra['value'] }}</b><br>
    @endforeach
    </small>
    @endif
    <hr>
    @endforeach

    <br>

    @if(ChuckRepeater::find($order->entry['location'])->type == 'delivery')
        <b>Subtotaal</b>: € {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }} <br>
        <b>Verzending</b>: € {{ number_format((float)$order->entry['order_shipping'], 2, ',', '.') }} <br><br>
        <b>Totaal</b>: € {{ number_format((float)$order->entry['order_price_with_shipping'], 2, ',', '.') }}
    @else
        <b>Totaal</b>: € {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }}
    @endif

    <br><br>

    Naam: {{ $order->entry['first_name'] . ' ' . $order->entry['last_name'] }} <br>
    Adres: {{ $order->entry['street'] . ' ' . $order->entry['housenumber'] }}, {{ $order->entry['postalcode'] . ' ' . $order->entry['city'] }} <br>
    E-mail: {{ $order->entry['email'] }} <br>
    Tel: {{ $order->entry['tel'] }} 
    @if(array_key_exists('remarks', $order->entry))
    Notities: {{ $order->entry['remarks'] }} 
    @endif
    @if(!$loop->last)
<div class="page-break"></div>
@endif
@endforeach
</div>
</body>
</html>
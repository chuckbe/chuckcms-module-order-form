@extends('chuckcms::backend.layouts.base')

@section('title')
Klant: {{ $customer->surname.' '.$customer->name }}
@endsection

@section('css')
	
@endsection

@section('scripts')
	
@endsection

@section('content')
<div class="container min-height">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
					<li class="breadcrumb-item"><a href="{{ route('dashboard.module.order_form.customers.index') }}">Klanten</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Klant: {{ $customer->surname.' '.$customer->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron jumbotron-fluid">
				<div class="container pl-5">
					<p class="lead">Details</p>

                    <b>Naam</b>: {{ $customer->surname . ' ' . $customer->name }} <br>
                    <b>E-mail</b>: {{ $customer->email }} 
                    @if(!is_null($customer->tel)) 
                    <br>
                    <b>Tel</b>: {{ $customer->tel }} 
                    @endif
                    <br>
                    @if(!is_null($customer->company))
                    <b>Bedrijfsnaam</b>: {{ $customer->json['company']['name'] }} <br>
                    <b>BTW-nummer</b>: {{ $customer->json['company']['vat'] }} <br>
                    @endif

                    @if(!is_null($customer->address))
                    <b>Adres</b>: <br> {{ $customer->json['address']['billing']['street'] . ' ' . $customer->json['address']['billing']['housenumber'] }}, <br> {{ $order->json['address']['billing']['postalcode'] . ' ' . $customer->json['address']['billing']['city'] .', '. config('chuckcms-module-order-form.countries_data.'.$customer->json['address']['billing']['country'].'.native') }} <br>
                    @if(!$customer->json['address']['shipping_equal_to_billing'])
                    <br>
                    <b>Verzend adres: </b><br> 
                    {{ $customer->json['address']['shipping']['street'] . ' ' . $customer->json['address']['shipping']['housenumber'] }}, <br> {{ $customer->json['address']['shipping']['postalcode'] . ' ' . $customer->json['address']['shipping']['city'] .', '. config('chuckcms-module-order-form.countries_data.'.$customer->json['address']['shipping']['country'].'.native') }} <br>
                    @endif
                    @endif
				</div>
			</div>
		</div>
	</div>
    {{-- <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" style="width:100%">
        			<thead>
        				<tr>
							<th scope="col">Product</th>
							<th scope="col">Hvl</th>
							<th scope="col" class="pr-5">Prijs</th>
							<th scope="col">Totaal</th>
        				</tr>
        			</thead>
        			@if(!array_key_exists('_price', array_values($order->json['products'])[0]))
        			<tbody>
						@foreach($order->json['products'] as $sku => $product)
						<tr class="order_line" data-id="{{ $sku }}">
							<td class="v-align-middle semi-bold">
								{{ $product['title'] }}
								@if($product['options'])
	                            <br>
	                            <small>{{ $product['options_text'] }}</small>
	                            @endif
	                            @isset($product['extras'])
	                            <br>
	                            <small>{{ $product['extras_text'] }}</small>
	                            @endisset
							</td>
							<td class="v-align-middle">{{ $product['quantity'] }}</td>
							<td class="v-align-middle">{{ ChuckEcommerce::formatPrice($product['price_tax'])  }} </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($product['total'])  }}</td>
						</tr>
						@endforeach
						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Subtotaal </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->subtotal + $order->subtotal_tax) }}</td>
						</tr>


						@if($order->hasDiscount)
						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Korting 
								@foreach($order->json['discounts'] as $discountKey => $discount)
								<br><small><b>{{ $discount['code'] }}</b>: -{{ $discount['value'] }}{{ $discount['type'] == 'percentage' ? '%' : '€' }}</small>
								@endforeach
							</td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->discount + $order->discount_tax) }}</td>
						</tr>
						@endif

						<tr class="shipping_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Verzending </td>
							<td class="v-align-middle semi-bold">{{ $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis' }}</td>
						</tr>
						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Totaal </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->final) }}</td>
						</tr>
        			</tbody>
        			@else
        			<tbody>
						@foreach($order->json['products'] as $sku => $product)
						<tr class="order_line" data-id="{{ $sku }}">
							<td class="v-align-middle semi-bold">
								{{ $product['title'] }}
								@if($product['options'])
	                            <br>
	                            <small>{{ $product['options_text'] }}</small>
	                            @endif
	                            @isset($product['extras'])
	                            <br>
	                            <small>{{ $product['extras_text'] }}</small>
	                            @endisset
							</td>
							<td class="v-align-middle">{{ $product['quantity'] }}</td>
							<td class="v-align-middle">{{ ChuckEcommerce::formatPrice($product['_price']['_unit'])  }} </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($product['_price']['_total'])  }}</td>
						</tr>
						@endforeach
						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Subtotaal </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->subtotal + ($order->isTaxed ? 0 : $order->subtotal_tax)) }}</td>
						</tr>


						@if($order->hasDiscount)
						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Korting 
								@foreach($order->json['discounts'] as $discountKey => $discount)
								<br><small><b>{{ $discount['code'] }}</b>: -{{ $discount['value'] }}{{ $discount['type'] == 'percentage' ? '%' : '€' }}</small>
								@endforeach
							</td>
							<td class="v-align-middle semi-bold">-{{ ChuckEcommerce::formatPrice($order->discount + $order->discount_tax) }}</td>
						</tr>
						@endif

						<tr class="shipping_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Verzending </td>
							<td class="v-align-middle semi-bold">{{ $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis' }}</td>
						</tr>
						<tr class="total_line">
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Totaal </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->final) }}</td>
						</tr>
        			</tbody>
        			@endif
        		</table>
        	</div>
        </div>
    </div> --}}
</div>

@endsection
@extends('chuckcms::backend.layouts.base')

@section('title')
	Orders
@endsection

@section('content')
<div class="container p-3">
	<div class="row">
		<div class="col-sm-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mt-3">
					<li class="breadcrumb-item active" aria-current="order">Orders</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="row px-3">
				<div class="col-sm-6 pr-sm-4 mb-3 mb-sm-0">
					<div class="row bg-light shadow-sm rounded">
						<div class="col-sm-12 p-3">
							<h3 class="mb-0">{{ count($orders) }}</h3>
							<small>bestellingen</small>
						</div>
					</div>
				</div>
				<div class="col-sm-6 pl-sm-4">
					<div class="row bg-light shadow-sm rounded">
						<div class="col-sm-12 p-3">
							<h3 class="mb-0">€ {{ number_format($total, 2, ',', '.') }}</h3>
							<small>totaal</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 my-3">
			<form action="" class="form-inline">
				<input type="date" name="startDate" value="{{ $startDate }}" class="form-control-sm mr-3 mb-3">
				<input type="date" name="endDate" value="{{ is_null($endDate) ? $startDate : $endDate }}" class="form-control-sm mr-3 mb-3">
				<select name="location" class="custom-select-sm mr-3 mb-3">
					<option value="0" @if(is_null($selectedLocation)) selected @endif>Alle locaties</option>
					@foreach(ChuckRepeater::for(config('chuckcms-module-order-form.locations.slug')) as $location)
					<option value="{{ $location->id }}" @if(!is_null($selectedLocation) && $selectedLocation->id == $location->id) selected @endif>{{ $location->name }}</option>
					@endforeach
				</select>
				<select name="type" class="custom-select-sm mr-3 mb-3">
					<option value="0" @if(is_null($type)) selected @endif>Alle types</option>
					<option value="web" @if(!is_null($type) && $type == 'web') selected @endif>Online</option>
					<option value="pos" @if(!is_null($type) && $type == 'pos') selected @endif>Winkel</option>
				</select>
				<select name="status" class="custom-select-sm mr-3 mb-3">
					<option value="0" @if(is_null($status)) selected @endif>Alle statussen</option>
					<option value="paid" @if(!is_null($status) && $status == 'paid') selected @endif>Betaald</option>
					<option value="expired" @if(!is_null($status) && $status == 'expired') selected @endif>Verlopen</option>
					<option value="canceled" @if(!is_null($status) && $status == 'canceled') selected @endif>Geannuleerd</option>
					<option value="awaiting" @if(!is_null($status) && $status == 'awaiting') selected @endif>In afwachting</option>
					<option value="refunded" @if(!is_null($status) && $status == 'refunded') selected @endif>Terugbetaling</option>
				</select>
				<button class="btn btn-sm btn-primary mr-3 mb-3" id="getOrdersForDate">Bekijken</button>
				<button class="btn btn-sm btn-outline-secondary mr-3 mb-3" id="excelOrdersForDate"><i class="fa fa-file-excel-o"></i></button>
				<button class="btn btn-sm btn-outline-secondary mb-3" id="pdfOrdersForDate"><i class="fa fa-file-pdf-o"></i></button>
			</form>
		</div>
		<div class="col-sm-12 my-3">
			<div class="table-responsive">
				<table class="table" data-datatable style="width:100%">
					<thead>
						<tr>
							<th scope="col">Order #</th>
							<th scope="col">Datum</th>
							<th scope="col">Naam & Adres</th>
							<th scope="col">Totaal</th>
							<th scope="col">Type</th>
							<th scope="col">Status</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						@php
						$module = ChuckSite::module('chuckcms-module-order-form');
						$ordersCount = strval(is_null($orders->first()) ? '0' : $orders->first()->id);
						@endphp
						@foreach($orders as $order)
							<tr class="order_line" data-id="{{ $order->id }}">
							    <td class="semi-bold">{{ str_pad($order->id, mb_strlen($ordersCount, 'utf8'), '0', STR_PAD_LEFT) }} <br> <small>#{{ $order->entry['order_number'] }}</small></td>
							    <td>{{ $order->entry['order_date'] }}</td>
							    <td>
							    	{{ $order->entry['first_name'] . ' ' . $order->entry['last_name'] }} <br> 
							    	<a href="mailto:{{ $order->entry['email'] }}">{{ $order->entry['email'] }}</a> <br> 
							    	
							    	@if($order->entry['tel'] !== null) 
							    	<a href="tel:{{ $order->entry['tel'] }}">{{ $order->entry['tel'] }}</a> <br> 
							    	@endif 

							    	@if($order->entry['street'] !== null)
							    	<small>{{ $order->entry['street'].' '.$order->entry['housenumber'].', '.$order->entry['postalcode'].' '.$order->entry['city'] }}</small>
							    	@endif
							    </td>
							    <td class="semi-bold">€ {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }}</td>
							    <td>
							    	<span class="badge badge-primary">{{ array_key_exists('type', $order->entry) ? $order->entry['type'] : 'web' }}</span></td>
							    <td>
							    	@if(array_key_exists('type', $order->entry) ? $order->entry['type'] == 'web' : true)
							    	@if( ($order->entry['status'] == 'paid') || ($module->getSetting('order.payment_upfront') == false && $order->entry['status'] == 'awaiting') )
							    		<span class="badge badge-success">{{ $order->entry['status'] }}</span>
						    		@else
							    		<span class="badge badge-warning">{{ $order->entry['status'] }}</span>
							    	@endif
							    	@elseif(array_key_exists('type', $order->entry) && $order->entry['type'] == 'pos')
							    		<span class="badge badge-{{ $order->entry['status'] == 'paid' ? 'success' : 'warning' }}">{{ $order->entry['status'] }}</span>
							    	@endif
							    </td>
							    <td class="semi-bold">
							    	<a href="{{ route('dashboard.module.order_form.orders.detail', ['order' => $order->id]) }}" class="btn btn-primary btn-sm btn-rounded d-inline-block">
							    		<i class="fa fa-eye"></i>
							    	</a>
							    	<div class="dropdown d-inline-block">
									  	<a class="btn btn-sm btn-outline-secondary btn-rounded" href="#" role="button" id="dropdownMenuLink{{ $order->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    	<i class="fa fa-ellipsis-v"></i>
									  	</a>

									  	<div class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $order->id }}">
									    	<a class="dropdown-item resendOrderConfirmationMail" href="#" data-id="{{ $order->id }}"><i class="fa fa-paper-plane-o"></i> Stuur bevestiging</a>
									    	@if(array_key_exists('invoice', $order->entry))
									    	<a class="dropdown-item" href="{{ route('dashboard.module.order_form.orders.invoice', ['order' => $order->id]) }}"><i class="fa fa-file-pdf-o"></i> Factuur</a>
									    	@endif
									  	</div>
									</div>
							    </td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css')

@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script>
$(document).ready(function() {
	let a_token = "{{ Session::token() }}"
	$('body').on('click', '#getOrdersForDate', function (event) {
		event.preventDefault();

		let startDate = $('input[name=startDate]').val();
		let endDate = $('input[name=endDate]').val();
		
		let selectedLocation = $('select[name=location]').find('option:selected').first().val();
		let type = $('select[name=type]').find('option:selected').first().val();
		let status = $('select[name=status]').find('option:selected').first().val();
		
		let url = '//'+location.host+location.pathname+'?';

		if (startDate == endDate) {
			url += 'date='+startDate;
		} else {
			url += 'date='+startDate+','+endDate;
		}

		if (selectedLocation != 0) {
			url += '&location='+selectedLocation;
		}

		if (type != 0) {
			url += '&type='+type;
		}

		if (status != 0) {
			url += '&status='+status;
		}

		window.location = url;
	});

	$('body').on('click', '#excelOrdersForDate', function (event) {
		event.preventDefault();

		let startDate = $('input[name=startDate]').val();
		let endDate = $('input[name=endDate]').val();
		
		let selectedLocation = $('select[name=location]').find('option:selected').first().val();
		let type = $('select[name=type]').find('option:selected').first().val();
		let status = $('select[name=status]').find('option:selected').first().val();
		
		let url = '//'+location.host+location.pathname+'/excel?';

		if (startDate == endDate) {
			url += 'date='+startDate;
		} else {
			url += 'date='+startDate+','+endDate;
		}

		if (selectedLocation != 0) {
			url += '&location='+selectedLocation;
		}

		if (type != 0) {
			url += '&type='+type;
		}

		if (status != 0) {
			url += '&status='+status;
		}

		window.location = url;
	});

	$('body').on('click', '#pdfOrdersForDate', function (event) {
		event.preventDefault();

		let startDate = $('input[name=startDate]').val();
		let endDate = $('input[name=endDate]').val();
		
		let selectedLocation = $('select[name=location]').find('option:selected').first().val();
		let type = $('select[name=type]').find('option:selected').first().val();
		let status = $('select[name=status]').find('option:selected').first().val();
		
		let url = '//'+location.host+location.pathname+'/pdf?';

		if (startDate == endDate) {
			url += 'date='+startDate;
		} else {
			url += 'date='+startDate+','+endDate;
		}

		if (selectedLocation != 0) {
			url += '&location='+selectedLocation;
		}

		if (type != 0) {
			url += '&type='+type;
		}

		if (status != 0) {
			url += '&status='+status;
		}

		window.location = url;
	});

	$('body').on('click', '.resendOrderConfirmationMail', function (event) {
		event.preventDefault();

		let order_id = $(this).attr('data-id');
		$.ajax({
	        method: 'POST',
	        url: "{{ route('dashboard.module.order_form.orders.resend_confirmation') }}",
	        data: { 
	            order_id: order_id,
	            _token: a_token
	        }
	    })
	    .done(function(data) {
	        if (data.status == "success"){
	            swal({
					title: 'Succes...',
					text: "Bevestiging verstuurd!"
				});
	        } else{
	            swal({
					icon: 'error',
					title: 'Error...',
					text: 'Er is iets misgegaan!',
				});
	        }

	    });
	});
});
</script>
@endsection
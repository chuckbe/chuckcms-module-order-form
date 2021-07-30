@extends('chuckcms::backend.layouts.base')

@section('title')
	Orders
@endsection

@section('add_record')
	
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
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		<div class="col-sm-12 my-3">
			<form action="" class="form-inline">
				<input type="date" name="startDate" value="{{ $startDate }}" class="form-control-sm ml-auto">
				<input type="date" name="endDate" value="{{ is_null($endDate) ? $startDate : $endDate }}" class="form-control-sm ml-3">
				<button class="btn btn-sm btn-primary ml-3" id="getOrdersForDate">Bekijken</button>
				<button class="btn btn-sm btn-outline-secondary ml-3" id="excelOrdersForDate"><i class="fa fa-file-excel-o"></i></button>
				<button class="btn btn-sm btn-outline-secondary ml-3" id="pdfOrdersForDate"><i class="fa fa-file-pdf-o"></i></button>
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
		window.location = '//'+location.host+location.pathname+'?date='+startDate+','+endDate;
	});

	$('body').on('click', '#excelOrdersForDate', function (event) {
		event.preventDefault();

		let startDate = $('input[name=startDate]').val();
		let endDate = $('input[name=endDate]').val();
		window.location = '//'+location.host+location.pathname+'/excel?date='+startDate+','+endDate;
	});

	$('body').on('click', '#pdfOrdersForDate', function (event) {
		event.preventDefault();

		let startDate = $('input[name=startDate]').val();
		let endDate = $('input[name=endDate]').val();
		window.location = '//'+location.host+location.pathname+'/pdf?date='+startDate+','+endDate;
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
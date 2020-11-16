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
		<div class="tools">
			<a class="collapse" href="javascript:;"></a>
			<a class="config" data-toggle="modal" href="#grid-config"></a>
			<a class="reload" href="javascript:;"></a>
			<a class="remove" href="javascript:;"></a>
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
							<th scope="col">Status</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($orders as $order)
							<tr class="order_line" data-id="{{ $order->id }}">
							    <td class="semi-bold">{{ $order->entry['order_number'] }}</td>
							    <td>{{ $order->entry['order_date'] }}</td>
							    <td>{{ $order->entry['first_name'] . ' ' . $order->entry['last_name'] }} <br> <a href="mailto:{{ $order->entry['email'] }}">{{ $order->entry['email'] }}</a> <br> @if($order->entry['tel'] !== null) <a href="tel:{{ $order->entry['tel'] }}">{{ $order->entry['tel'] }}</a> <br> @endif <small>{{ $order->entry['street'].' '.$order->entry['housenumber'].', '.$order->entry['postalcode'].' '.$order->entry['city'] }}</small></td>
							    <td class="semi-bold">€ {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }}</td>
							    <td>
							    	@if( (config('chuckcms-module-order-form.order.payment_upfront') && $order->entry['status'] == 'paid') || (config('chuckcms-module-order-form.order.payment_upfront') == false && $order->entry['status'] == 'awaiting') )
							    		<span class="label label-inverse">{{ $order->entry['status'] }}</span>
							    		@else
							    		<span class="label">{{ $order->entry['status'] }}</span>
							    	@endif
							    </td>
							    <td class="semi-bold">
							    	<a href="{{ route('dashboard.module.order_form.orders.detail', ['order' => $order->id]) }}" class="btn btn-default btn-sm btn-rounded m-r-20">
							    		<i data-feather="clipboard"></i> bekijken
							    	</a>
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
    <link href="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.chuck.be/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('scripts')
	<script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.chuck.be/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.chuck.be/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script src="https://cdn.chuck.be/assets/js/tables.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
    
@endsection
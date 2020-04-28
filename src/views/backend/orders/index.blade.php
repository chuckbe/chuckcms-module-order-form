@extends('chuckcms::backend.layouts.admin')

@section('title')
	Orders
@endsection

@section('add_record')
	
@endsection

@section('css')
	<link href="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
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

@section('content')
<div class=" container-fluid container-fixed-lg">
    <div class="row">
		<div class="col-lg-12">
		<!-- START card -->
			<div class="card card-transparent">
				<div class="card-header ">
					<div class="card-title">Orders</div>
					<div class="tools">
						<a class="collapse" href="javascript:;"></a>
						<a class="config" data-toggle="modal" href="#grid-config"></a>
						<a class="reload" href="javascript:;"></a>
						<a class="remove" href="javascript:;"></a>
					</div>
					<div class="pull-right">
				    	<div class="col-xs-12">
				    		<input type="text" id="search-table" class="form-control pull-right" placeholder="Search">
				    	</div>
				    </div>
				    <div class="clearfix"></div>
				</div>
				<div class="card-block">
					<div class="table-responsive">
						<table class="table table-hover table-condensed" id="condensedTable" data-table-count="30">
						<thead>
							<tr>
								<th style="width:12%">Order #</th>
								<th style="width:13%">Datum</th>
								<th style="width:34%">Naam & Adres</th>
								<th style="width:12%">Totaal</th>
								<th style="width:13%">Status</th>
								<th style="width:16%">Actions</th>
							</tr>
						</thead>
							<tbody>
								@foreach($orders as $order)
								<tr class="order_line" data-id="{{ $order->id }}">
							    	<td class="v-align-middle semi-bold">{{ $order->entry['order_number'] }}</td>
							    	<td class="v-align-middle">{{ $order->entry['order_date'] }}</td>
							    	<td class="v-align-middle">{{ $order->entry['first_name'] . ' ' . $order->entry['last_name'] }} <br> <a href="mailto:{{ $order->entry['email'] }}">{{ $order->entry['email'] }}</a> <br> @if($order->entry['tel'] !== null) <a href="tel:{{ $order->entry['tel'] }}">{{ $order->entry['tel'] }}</a> <br> @endif <small>{{ $order->entry['street'].' '.$order->entry['housenumber'].', '.$order->entry['postalcode'].' '.$order->entry['city'] }}</small></td>
							    	<td class="v-align-middle semi-bold">€ {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }}</td>
							    	<td class="v-align-middle">
							    		@if( (config('chuckcms-module-order-form.order.payment_upfront') && $order->entry['status'] == 'paid') || (config('chuckcms-module-order-form.order.payment_upfront') == false && $order->entry['status'] == 'awaiting') )
							    		<span class="label label-inverse">{{ $order->entry['status'] }}</span>
							    		@else
							    		<span class="label">{{ $order->entry['status'] }}</span>
							    		@endif
							    	</td>
							    	<td class="v-align-middle semi-bold">
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
		<!-- END card -->
		</div>
    </div>
</div>
@endsection
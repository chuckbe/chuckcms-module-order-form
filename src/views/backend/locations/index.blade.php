@extends('chuckcms::backend.layouts.base')

@section('title')
Locaties
@endsection

@section('css')
@endsection

@section('scripts')
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script>
$( document ).ready(function() { 
	init();
	function init () {
		//init media manager inputs 
		var domain = "{{ URL::to('dashboard/media')}}";
		$('.img_lfm_link').filemanager('image', {prefix: domain});
	}
});
function editModal(id, name, type, days_of_week_disabled, on_the_spot, dates_disabled, delivery_cost, delivery_free_from, delivery_limited_to, delivery_radius, delivery_radius_from, delivery_in_postalcodes, time_required, time_min, time_max, pos_users, pos_name, pos_address1, pos_address2, pos_vat, pos_receipt_title, pos_receipt_footer_line1, pos_receipt_footer_line2, pos_receipt_footer_line3, order){
	$('#edit_location_id').val(id);
	$('#edit_location_name').val(name);
	
	if (type == 'takeout') {
		$('#edit_location_type').find('option').first().prop('selected', true);
		$('#edit_location_type').find('option').last().prop('selected', false);
	} else if (type == 'delivery') {
		$('#edit_location_type').find('option').first().prop('selected', false);
		$('#edit_location_type').find('option').last().prop('selected', true);
	}

	$('#edit_location_days_of_week_disabled').val(days_of_week_disabled);

	if (on_the_spot == 1) {
		$('#edit_location_on_the_spot').prop('checked', true);
	} else {
		$('#edit_location_on_the_spot').prop('checked', false);
	}

	$('#edit_location_dates_disabled').val(dates_disabled);
	$('#edit_location_delivery_cost').val(delivery_cost);
	$('#edit_location_delivery_free_from').val(delivery_free_from);
	if (delivery_limited_to == '') {
		$('#edit_location_delivery_limited_to').find('option').eq(0).prop('selected', true);
		$('#edit_location_delivery_limited_to').find('option').eq(1).prop('selected', false);
		$('#edit_location_delivery_limited_to').find('option').eq(2).prop('selected', false);
	} else if (delivery_limited_to == 'postalcode') {
		$('#edit_location_delivery_limited_to').find('option').eq(0).prop('selected', false);
		$('#edit_location_delivery_limited_to').find('option').eq(1).prop('selected', true);
		$('#edit_location_delivery_limited_to').find('option').eq(2).prop('selected', false);
	} else if (delivery_limited_to == 'radius') {
		$('#edit_location_delivery_limited_to').find('option').eq(0).prop('selected', false);
		$('#edit_location_delivery_limited_to').find('option').eq(1).prop('selected', false);
		$('#edit_location_delivery_limited_to').find('option').eq(2).prop('selected', true);
	}
	$('#edit_location_delivery_radius').val(delivery_radius == 'null' ? 0 : delivery_radius);
	$('#edit_location_delivery_radius_from').val(delivery_radius_from);
	$('#edit_location_delivery_in_postalcodes').val(delivery_in_postalcodes);

	if (time_required == 1) {
		$('#edit_location_time_required').prop('checked', true);
	} else {
		$('#edit_location_time_required').prop('checked', false);
	}

	$('#edit_location_time_min').val(time_min);
	$('#edit_location_time_max').val(time_max);

	$('#edit_location_pos_users').val(pos_users);
	$('#edit_location_pos_name').val(pos_name);
	$('#edit_location_pos_address1').val(pos_address1);
	$('#edit_location_pos_address2').val(pos_address2);
	$('#edit_location_pos_vat').val(pos_vat);
	$('#edit_location_pos_receipt_title').val(pos_receipt_title);
	$('#edit_location_pos_receipt_footer_line1').val(pos_receipt_footer_line1);
	$('#edit_location_pos_receipt_footer_line2').val(pos_receipt_footer_line2);
	$('#edit_location_pos_receipt_footer_line3').val(pos_receipt_footer_line3);

	$('#edit_location_order').val(order);
	
	$('#editLocationModal').modal('show');
}

function deleteModal(id, name){
	$('#delete_location_id').val(id);
	$('#delete_location_name').text(name);
	$('#deleteLocationModal').modal('show');
}
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Locaties</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createLocationModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Locatie</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Type</th>
							<th scope="col">Volgorde</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($locations as $location)
						<tr>
							<td class="v-align-middle">{{ $location->id }}</td>
							<td class="v-align-middle">{{$location->json['name'] }}</td>
							<td class="v-align-middle">
								<span class="badge badge-{{ $location->type == 'takeout' ? 'primary' : 'secondary' }}">
									{{$location->type}}
								</span>
							</td>
							<td class="v-align-middle">{{$location->order }}</td>
							<td class="v-align-middle semi-bold">
								@can('edit redirects')
								<a href="#" onclick="editModal({{ $location->id }}, '{{ $location->json['name'] }}', '{{ $location->type }}', '{{ $location->days_of_week_disabled }}', '{{ $location->on_the_spot ? '1' : '0' }}', '{{ $location->dates_disabled }}', '{{ $location->delivery_cost == 0 ? '0' : $location->delivery_cost }}', '{{ $location->delivery_free_from == 0 ? '0' : $location->delivery_free_from }}', '{{ $location->delivery_limited_to }}', '{{ $location->delivery_radius == null ? 'null' : $location->delivery_radius }}', '{{ $location->delivery_radius_from }}', '{{ implode(',', array_filter($location->json['delivery_in_postalcodes'])) }}', '{{ $location->time_required ? '1' : '0' }}', '{{ $location->time_min == 0 ? '0' : $location->time_min }}', '{{ $location->time_max }}', '{{ $location->pos_users }}', '{{ $location->pos_name }}', '{{ $location->pos_address1 }}', '{{ $location->pos_address2 }}', '{{ $location->pos_vat }}', '{{ $location->pos_receipt_title }}', '{{ $location->pos_receipt_footer_line1 }}', '{{ $location->pos_receipt_footer_line2 }}', '{{ $location->pos_receipt_footer_line3 }}', '{{ $location->order }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
									<i data-feather="edit-2"></i> edit
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $location->id }}, '{{ $location->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
									<i data-feather="trash"></i> delete
								</a>
								@endcan
							</td>
						</tr>
						@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
@include('chuckcms-module-order-form::backend.locations._create_modal')
@include('chuckcms-module-order-form::backend.locations._edit_modal')
@include('chuckcms-module-order-form::backend.locations._delete_modal')
@endsection
@extends('chuckcms::backend.layouts.base')

@section('title')
Beloningen
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
function editModal(id, name, points, image, discount){
	$('#edit_reward_id').val(id);
	$('#edit_reward_name').val(name);
	$('#edit_reward_points').val(points);
	$('#edit_reward_logo').val(image);
	$('#editrewardholder').attr('src', "{{ ChuckSite::getSite('domain') }}/"+image);
	$('#edit_reward_discount').find('option').prop('selected', false);
	$('#edit_reward_discount').find('option[value="'+discount+'"]').prop('selected', true);
	$('#editRewardModal').modal('show');
}

function deleteModal(id, name){
	$('#delete_reward_id').val(id);
	$('#delete_reward_name').text(name);
	$('#deleteRewardModal').modal('show');
}
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Beloningen</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createRewardModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Beloning</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Punten</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($rewards as $reward)
						<tr>
							<td class="v-align-middle">{{ $reward->id }}</td>
							<td class="v-align-middle">{{$reward->name }}</td>
							<td class="v-align-middle">
								<span class="badge badge-primary">{{ $reward->points }}</span>
							</td>
							<td class="v-align-middle semi-bold">
								@can('edit redirects')
								<a href="#" onclick="editModal({{ $reward->id }}, '{{ $reward->name }}', '{{ $reward->points }}', '{{ $reward->image }}', '{{ $reward->discount }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
									<i data-feather="edit-2"></i> edit
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $reward->id }}, '{{ $reward->name }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
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
@include('chuckcms-module-order-form::backend.rewards._create_modal')
@include('chuckcms-module-order-form::backend.rewards._edit_modal')
@include('chuckcms-module-order-form::backend.rewards._delete_modal')
@endsection
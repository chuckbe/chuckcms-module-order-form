@extends('chuckcms::backend.layouts.base')

@section('title')
Categorieën
@endsection

@section('add_record')
@can('create redirects')
<a href="#" data-target="#createCategoryModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuwe Categorie Toe</a>
@endcan
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
function editModal(id, name, is_displayed, order){
	$('#edit_category_id').val(id);
	$('#edit_category_name').val(name);
	
	if (is_displayed == 1) {
		$('#edit_category_is_displayed').prop('checked', true);
	} else {
		$('#edit_category_is_displayed').prop('checked', false);
	}

	$('#edit_category_order').val(order);
	
	$('#editCategoryModal').modal('show');
}

function deleteModal(id, name){
	$('#delete_category_id').val(id);
	$('#delete_category_name').text(name);
	$('#deleteCategoryModal').modal('show');
}
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Categorieën</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createCategoryModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Categorie</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Wordt weergegeven?</th>
							<th scope="col">Volgorde</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($categories as $category)
						<tr>
							<td class="v-align-middle">{{ $category->id }}</td>
							<td class="v-align-middle">{{$category->json['name'] }}</td>
							<td class="v-align-middle">
								<span class="badge badge-{{ $category->is_displayed ? 'success' : 'danger' }}">
									{!!$category->is_displayed ? '✓' : '✕'!!}
								</span>
							</td>
							<td class="v-align-middle">{{$category->order }}</td>
							<td class="v-align-middle semi-bold">
								@can('edit redirects')
								<a href="#" onclick="editModal({{ $category->id }}, '{{ $category->json['name'] }}', '{{ $category->is_displayed }}', '{{ $category->order }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
									<i data-feather="edit-2"></i> edit
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $category->id }}, '{{ $category->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
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
@include('chuckcms-module-order-form::backend.categories._create_modal')
@include('chuckcms-module-order-form::backend.categories._edit_modal')
@include('chuckcms-module-order-form::backend.categories._delete_modal')
@endsection
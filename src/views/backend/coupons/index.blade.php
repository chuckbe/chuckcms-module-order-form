@extends('chuckcms::backend.layouts.base')

@section('title')
Coupons
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
                    <li class="breadcrumb-item active" aria-current="page">Coupons</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createCategoryModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Coupon</a>
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
        				
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>

@endsection
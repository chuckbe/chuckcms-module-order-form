@extends('chuckcms::backend.layouts.base')

@section('title')
	Producten
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.order_form.products.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Product Toe</a>
	@endcan
@endsection

@section('content')
<div class="container p-3">
	<div class="row">
		<div class="col-sm-12">
			<nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="product">Producten</li>
                </ol>
            </nav>
		</div>
	</div>
	<div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		@can('create forms')
		<div class="col-sm-12 text-right">
    		<a href="{{ route('dashboard.module.order_form.products.create') }}" class="btn btn-sm btn-outline-success"> Voeg Nieuw Product Toe </a>
    	</div>
		@endcan
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
							<th scope="col">ID</th>
							<th scope="col">Naam</th>
							<th scope="col">Slug</th>
							<th scope="col">Categorie</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($products as $product)
							<tr class="product_line" data-id="{{ $product->id }}">
								<td>{{ $product->id }}</td>
						    	<td class="semi-bold">{{ $product->name[ChuckSite::getFeaturedLocale()] }}</td>
						    	<td>{{$product->slug}}</td>
								<td>{{ChuckRepeater::find($product->category)->name}}</td>
						    	{{-- <td>{{config('chuckcms-module-order-form.categories')[$product->json['category']]['name']}}</td> --}}
						    	<td class="semi-bold">
						    		<a href="{{ route('dashboard.module.order_form.products.edit', ['product' => $product->id]) }}" class="btn btn-primary btn-sm btn-rounded m-r-20">
						    			<i class="fa fa-pencil"></i> edit
						    		</a>
						    		<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 product_delete" data-id="{{ $product->id }}">
						    			<i class="fa fa-trash"></i> delete
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
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script>
$( document ).ready(function (){
	$('.product_delete').each(function(){
		var product_id = $(this).attr("data-id");
		var token = '{{ Session::token() }}';
	  	$(this).click(function (event) {
	  		event.preventDefault();
	  		swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
			  	if (result.value) { 
			  		$.ajax({
                        method: 'POST',
                        url: "{{ route('dashboard.module.order_form.products.delete') }}",
                        data: { 
                        	product_id: product_id, 
                        	_token: token
                        }
                    })
                    .done(function (data) {
                    	if(data == 'success'){
                    		$(".product_line[data-id='"+product_id+"']").first().remove();
                    		swal(
					      		'Deleted!',
					      		'The product has been deleted.',
					      		'success'
					    	)
                    	} else {
                    		swal(
					      		'Oops!',
					      		'Something went wrong...',
					      		'danger'
					    	)
                    	}
                    });
			  	}
			})
	    });
	});
});
</script>
@endsection
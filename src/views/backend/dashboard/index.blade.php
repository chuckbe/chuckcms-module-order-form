@extends('chuckcms::backend.layouts.admin')

@section('title')
	Order Form
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.order_form.index') }}">Order Form</a></li>
	</ol>
@endsection

@section('content')
<div class="card-block">
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-default">
				<div class="card-header  separator">
					<div class="card-title">SITE DATA</div>
				</div>
				<div class="card-block">

				</div>
			</div>
		</div>
	</div>
</div>


@endsection

@section('scripts')

@endsection
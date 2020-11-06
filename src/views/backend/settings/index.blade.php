@extends('chuckcms::backend.layouts.base')

@section('title')
	Settings
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.order_form.settings.index') }}">Settings</a></li>
	</ol>
@endsection

@section('content')
<div class="container p-3">
  <form action="{{ route('dashboard.module.order_form.products.update') }}" method="POST">
    <div class="row">
      <div class="col-sm-12">
        <nav aria-label="breadcumb mt-3">
          <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item active" aria-current="instellingen">Bewerk instellingen</li>
          </ol>
        </nav>
      </div>
    </div>
    @if ($errors->any())
      <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
        <div class="col">
          <div class="my-3">
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col">
        <div class="my-3">
          <ul class="nav nav-tabs justify-content-start" id="instellingenTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="categories_setup-tab" data-target="#tab_resource_categories_setup" data-toggle="tab" href="#" role="tab" aria-controls="#categories_setup" aria-selected="true">Categories</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="form_setup-tab" data-target="#tab_resource_form_setup" data-toggle="tab" href="#" role="tab" aria-controls="#form_setup" aria-selected="false">Form</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="cart_setup-tab" data-target="#tab_resource_cart_setup" data-toggle="tab" href="#" role="tab" aria-controls="#cart_setup" aria-selected="false">Cart</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="order_setup-tab" data-target="#tab_resource_order_setup" data-toggle="tab" href="#" role="tab" aria-controls="#order_setup" aria-selected="false">Order</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="emails_setup-tab" data-target="#tab_resource_emails_setup" data-toggle="tab" href="#" role="tab" aria-controls="#emails_setup" aria-selected="false">Emails</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="locations_setup-tab" data-target="#tab_resource_locations_setup" data-toggle="tab" href="#" role="tab" aria-controls="#locations_setup" aria-selected="false">Locations</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="delivery_setup-tab" data-target="#tab_resource_delivery_setup" data-toggle="tab" href="#" role="tab" aria-controls="#delivery_setup" aria-selected="false">Delivery</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="instellingenTabContent">
      {{-- categories-tab-starts --}}
      <div class="col-sm-12 tab-pane fade show active" id="tab_resource_categories_setup" role="tabpanel" aria-labelledby="categories_setup-tab">
        <h4>Categories</h4>
        @foreach ($settings["categories"] as $categoryName => $categoryValue)
          <div class="row column-seperation">
            <div class="col">
              <div class="form-group form-group-default required ">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="name" name="{{$categoryValue["name"]}}" value="{{$categoryValue["name"]}}" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group form-group-default required ">
                <label>Deze categorie tonen</label>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="is_displayed" @if($categoryValue["is_displayed"] == 1) checked @endif>
                  <label class="form-check-label" for="is_displayed">is displayed</label>
                </div>
              </div>
            </div>
          </div>
          <hr>
        @endforeach
      </div>{{-- categories-tab-ends --}}
      {{-- form-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_form_setup" role="tabpanel" aria-labelledby="form_setup-tab">
        <h4>Form</h4>
        <br>
        @foreach ($settings["form"] as $formOption => $formOptionValue)
          <div class="row column-seperation">
            <div class="col">
              @if (is_bool($formOptionValue))
                <div class="form-group form-group-default required ">
                  <label>{{$formOption}}</label>
                  <select class="full-width select2" data-init-plugin="select2" name="{{$formOption}}">
                    <option value="true" @if($formOptionValue == true) selected @endif>Ja</option>
                    <option value="false" @if($formOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              @else
                <div class="form-group form-group-default required ">
                  <label>{{$formOption}}</label>
                  <input type="text" class="form-control" placeholder="{{$formOption}} name" name="{{$formOption}}" value="{{$formOptionValue}}" required>
                </div>
              @endif
            </div>
          </div>
          <hr>
        @endforeach
      </div>{{-- form-tab-ends --}}
      {{-- cart-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_cart_setup" role="tabpanel" aria-labelledby="cart_setup-tab">
        <h4>Cart</h4>
        <div class="row column-seperation">  
          <div class="col"> 
            <div class="form-group form-group-default required ">
              <label>Use ui</label>
              <select class="full-width select2" data-init-plugin="select2" name="use_ui">
                <option value="true" @if($settings["cart"]["use_ui"] == true) selected @endif>Ja</option>
                <option value="false" @if($settings["cart"]["use_ui"] !== true) selected @endif>Nee</option>
              </select>
            </div>
          </div>
        </div>
      </div>{{-- cart-tab-ends --}}
      {{-- order-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_order_setup" role="tabpanel" aria-labelledby="order_setup-tab">
        <h4>Order</h4>
        @foreach ($settings["order"] as $order => $orderValue)
          @if (is_bool($orderValue))
            <div class="row column-seperation">  
              <div class="col"> 
                <div class="form-group form-group-default required ">
                  <label>{{$order}}</label>
                  <select class="full-width select2" data-init-plugin="select2" name="{{$order}}">
                    <option value="true" @if($orderValue == true) selected @endif>Ja</option>
                    <option value="false" @if($orderValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              </div>
            </div>
          @else
            <div class="form-group form-group-default required ">
              <label>{{$order}}</label>
              <input type="text" class="form-control" placeholder="{{$orderValue}} name" name="{{$order}}" value="{{$orderValue}}" required>
            </div>
          @endif
        @endforeach
      </div>{{-- order-tab-ends --}}
      {{-- emails-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_emails_setup" role="tabpanel" aria-labelledby="emails_setup-tab">
        <h4>Emails</h4>
        @foreach ($settings["emails"] as $emailOption => $emailOptionValue)
          @if (is_bool($emailOptionValue))
            <div class="row column-seperation">  
              <div class="col"> 
                <div class="form-group form-group-default required ">
                  <label>{{$emailOption}}</label>
                  <select class="full-width select2" data-init-plugin="select2" name="{{$emailOption}}">
                    <option value="true" @if($emailOptionValue == true) selected @endif>Ja</option>
                    <option value="false" @if($emailOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              </div>
            </div>
          @else
            <div class="form-group form-group-default required ">
              <label>{{$emailOption}}</label>
              <input type="text" class="form-control" placeholder="{{$emailOptionValue}} name" name="{{$order}}" value="{{$emailOptionValue}}" required>
            </div>
          @endif
        @endforeach
      </div>{{-- emails-tab-ends --}}
      {{-- locations-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_locations_setup" role="tabpanel" aria-labelledby="locations_setup-tab">
        <h4>Locations</h4>
        @foreach ($settings["locations"] as $locationOption => $locationOptionValue)
          <h5>{{ucfirst($locationOption)}}</h5>
          <div class="row column-seperation">  
            <div class="col">
              @foreach ($locationOptionValue as $locationOptionType => $locationOptionTypeValue)
                @if (is_bool($locationOptionTypeValue))
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <select class="full-width select2" data-init-plugin="select2" name="{{$locationOptionType}}">
                      <option value="true" @if($locationOptionTypeValue == true) selected @endif>Ja</option>
                      <option value="false" @if($locationOptionTypeValue !== true) selected @endif>Nee</option>
                    </select>
                  </div>
                @elseif (is_null($locationOptionTypeValue))
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <input type="text" class="form-control" placeholder="N/A" name="{{$locationOptionType}}" 
                    value=""
                    required>
                  </div>
                @elseif (is_array($locationOptionTypeValue))
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <input type="text" class="form-control" placeholder="voer elke postcode in met een komma" name="{{$locationOptionType}}" 
                    value="{{implode(",", $locationOptionTypeValue)}}"
                    required>
                  </div>
                @else
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <input type="text" class="form-control" placeholder="{{$locationOptionTypeValue}} name" name="{{$locationOptionType}}" value="{{$locationOptionTypeValue}}" required>
                  </div>
                @endif 
              @endforeach 
            </div>
          </div>
          <hr>
        @endforeach
      </div>{{-- locations-tab-ends --}}
      {{-- delivery-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_delivery_setup" role="tabpanel" aria-labelledby="delivery_setup-tab">
        <h4>Delivery</h4>
        @foreach ($settings["delivery"] as $deliveryOption => $deliveryOptionValue)
          <div class="row column-seperation"> 
            <div class="col">
              @if (is_bool($deliveryOptionValue))
                <div class="form-group form-group-default required ">
                  <label>{{$deliveryOption}}</label>
                  <select class="full-width select2" data-init-plugin="select2" name="{{$deliveryOption}}">
                    <option value="true" @if($deliveryOptionValue == true) selected @endif>Ja</option>
                    <option value="false" @if($deliveryOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              @else
                <div class="form-group form-group-default required ">
                  <label>{{$deliveryOption}}</label>
                  <input type="text" class="form-control" placeholder="{{$deliveryOption}} details" name="{{$deliveryOption}}" value="{{$deliveryOptionValue}}" required>
                </div>
                {{-- {{gettype($deliveryOptionValue)}} --}}
              @endif
            </div>
          </div>
        @endforeach
      </div>{{-- delivery-tab-ends --}}
    </div>
    <div class="row">
      <div class="col">
        <p class="pull-right">
          <input type="hidden" name="_token" value="{{ Session::token() }}">
          <input type="hidden" name="product_id" value="">
          <button type="submit" name="create" class="btn btn-success btn-cons pull-right m-1" value="1">Opslaan</button>
          <a href="{{ route('dashboard.module.order_form.products.index') }}" class="pull-right m-1"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
        </p>
      </div>
    </div>
  </form>
</div>
@endsection

@section('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <link href="//cdn.chuck.be/assets/plugins/summernote/css/summernote.css" rel="stylesheet" media="screen">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script src="//cdn.chuck.be/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script src="//cdn.chuck.be/assets/plugins/summernote/js/summernote.min.js"></script>
<script>
$( document ).ready(function() { 
  init(); 

  function init() {
    //Autonumeric plug-in
    $('.autonumeric').autoNumeric('init');

    //init media manager inputs 
    var domain = "{{ URL::to('dashboard/media')}}";
    $('.img_lfm_link').filemanager('image', {prefix: domain});
  }
    
  $('.summernote-text-editor').summernote({
    height: 150,
    fontNames: ['Arial', 'Arial Black', 'Open Sans', 'Helvetica', 'Helvetica Neue', 'Lato'],
    toolbar: [
      // [groupName, [list of button]]
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']]
    ]
  });
  
});
</script>
@endsection
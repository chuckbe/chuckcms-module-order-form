@extends('chuckcms::backend.layouts.base')

@section('title')
Bestelling #{{ $order->entry['order_number'] }}
@endsection

@section('add_record')
  
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.order_form.orders.index') }}">Bestellingen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bestelling #{{ $order->entry['order_number'] }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
        <div class="col-sm-12 my-3">
          <h5>Gegevens</h5>
          <div class="table-responsive">
            <table class="table" style="width:100%">
              <tbody>
                @foreach($order->entry as $entryKey => $entryValue)
                @if(!is_array($entryValue))
                <tr>
                  <td>{{ $entryKey }}</td>
                  <td>{{ $entryValue }} 
                    @if($entryKey == 'order_date') <button class="btn btn-xs float-right btn-primary editDateModal"><i class="fas fa fa-edit"></i></button> @endif
                    @if($entryKey == 'street') <button class="btn btn-xs float-right btn-primary editAddressModal"><i class="fas fa fa-edit"></i></button> @endif </td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
        <div class="col-sm-12 my-3">
          <h5>Overzicht</h5>
          <div class="table-responsive">
            <table class="table" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">Product</th>
                  <th scope="col">Hvl</th>
                  <th scope="col" class="pr-5">Prijs</th>
                </tr>
              </thead>

              <tbody>
                @foreach($order->entry['items'] as $item)
                <tr class="order_line" data-id="{{ $item['id'] }}">
                  <td>
                    {{ $item['attributes'] == false ? $item['name'] : $item['name'] . ' - ' . $item['attributes'] }} <br>
                    @if($item['options'] !== false)
                    <small>
                    @foreach($item['options'] as $option)
                    {{ $option['name'] }}: {{ $option['value'] }}<br>
                    @endforeach
                    </small>
                      @if(array_key_exists('extras', $item) && $item['extras'] !== false)
                      <br>
                      @endif
                    @endif

                    @if(array_key_exists('extras', $item) && $item['extras'] !== false)
                    <small>
                    @foreach($item['extras'] as $option)
                    {{ $option['name'] }} (€ {{ $option['value'] }})<br>
                    @endforeach
                    </small>
                    @endif
                  </td>
                  <td>{{ $item['qty'] }}x</td>
                  <td>{{ $item['totprice'] }}</td>
                </tr>
                @endforeach
                <tr>
                  <td><b>Totaal</b></td>
                  <td> </td>
                  <td>{{ $order->entry['order_price'] }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
</div>

@include('chuckcms-module-order-form::backend.orders._edit_date_modal')
@include('chuckcms-module-order-form::backend.orders._edit_address_modal')

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

  $('body').on('click', '.editDateModal', function (event) {
    event.preventDefault();
    
    $('#editDateModal').modal('show');
  });

  $('body').on('click', '.editAddressModal', function (event) {
    event.preventDefault();
    
    $('#editAddressModal').modal('show');
  });

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
@if (session('notification'))
  @include('chuckcms::backend.includes.notification')
@endif
@endsection
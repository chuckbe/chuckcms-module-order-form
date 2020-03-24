@extends('chuckcms::backend.layouts.admin')

@section('title')
Bestelling #{{ $order->entry['order_number'] }}
@endsection

@section('breadcrumbs')
<ol class="breadcrumb">
	<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.order_form.orders.index') }}">Bestellingen</a></li>
  <li class="breadcrumb-item"><a href="#">Bestelling #{{ $order->entry['order_number'] }}</a></li>
</ol>
@endsection

@section('content')
<!-- START CONTAINER FLUID -->
<div class="container-fluid   container-fixed-lg">
  <!-- START card -->
  <div class="card-block">
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-default">
          <div class="card-block">
            <div class="row">
              <div class="col-sm-12">
                <h4>Gegevens</h4>
              </div>
              <div class="col-sm-12">
                  <table class="table table-inline">
                    <tbody>
                      @foreach($order->entry as $entryKey => $entryValue)
                      @if(!is_array($entryValue))
                      <tr>
                        <td>{{ $entryKey }}</td>
                        <td>{{ $entryValue }}</td>
                      </tr>
                      @endif
                      @endforeach
                    </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <h4 class="mt-5">Overzicht</h4>
              </div>
              <div class="col-sm-12">
                  <table class="table table-inline">
                    <tbody>
                      @foreach($order->entry['items'] as $entryValue)
                        @if($entryValue['attributes'])
                          @foreach($entryValue['attributes_list'] as $item)
                          <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['qty'] }}x</td>
                            <td>{{ $item['totprice'] }}</td>
                          </tr>
                          @endforeach
                        @else
                        <tr>
                          <td>{{ $entryValue['name'] }}</td>
                          <td>{{ $entryValue['qty'] }}x</td>
                          <td>{{ $entryValue['totprice'] }}</td>
                        </tr>
                        @endif
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
      </div>
    </div>
  </div>

</div>
<!-- END CONTAINER FLUID -->
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
@extends('chuckcms::backend.layouts.admin')

@section('content')
<!-- START CONTAINER FLUID -->
<div class=" container-fluid   container-fixed-lg">

<!-- START card -->
<form action="{{ route('dashboard.module.order_form.products.update') }}" method="POST">
<div class="card card-transparent">
  <div class="card-header ">
    <div class="card-title">Bewerk huidig product</div>
  </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="card card-transparent">
  <div class="card-block">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-transparent">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs nav-tabs-linetriangle" data-init-reponsive-tabs="dropdownfx">
            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
              <li class="nav-item">
                <a href="#" @if($loop->iteration == 1) class="active" @endif data-toggle="tab" data-target="#tab_product_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
              </li>
            @endforeach
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">

            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
            <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_product_{{ $langKey }}">
              <div class="form-group form-group-default required">
                <label>Naam *</label>
                <input type="text" class="form-control" placeholder="Titel" name="name[{{ $langKey }}]" value="{{ old('name.'.$langKey, $product->json['name'][$langKey]) }}" required>
              </div>
              <div class="form-group form-group-default required">
                <label>Beschrijving *</label>
                <textarea class="form-control" placeholder="Beschrijving" name="description[{{ $langKey }}]" rows="2" style="height:50px;" required>{{ old('description.'.$langKey, $product->json['description'][$langKey]) }}</textarea>
              </div>
            </div>
            @endforeach

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END card -->

<div class="card-block">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-12">

              <div class="form-group form-group-default required ">
                <label>Categorie</label>
                <select class="full-width" data-init-plugin="select2" name="category" data-minimum-results-for-search="-1">
                  @foreach(config('chuckcms-module-order-form.categories') as $categoryKey => $category)
                    <option value="{{ $categoryKey }}" @if(array_key_exists('category', $product->json)) @if($categoryKey == $product->json['category']) selected @endif @endif>{{ $category['name'] }}</option>
                  @endforeach
                </select>
              </div>

            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-group-default input-group">
                <div class="form-input-group">
                  <label class="inline">Wordt weergegeven?</label>
                </div>
                <div class="input-group-addon bg-transparent h-c-50">
                  <input type="hidden" name="is_displayed" value="0">
                  <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_displayed" {{ old('is_displayed', $product->json['is_displayed']) == '1' ? 'checked' : '' }} />
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-default input-group">
                <div class="form-input-group">
                  <label class="inline">Mag verkocht worden?</label>
                </div>
                <div class="input-group-addon bg-transparent h-c-50">
                  <input type="hidden" name="is_buyable" value="0">
                  <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_buyable" {{ old('is_buyable', $product->json['is_buyable']) == '1' ? 'checked' : '' }} />
                </div>
              </div>
            </div>
          </div>
          
          <hr>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-group-default required">
                <label>Verkoopprijs met BTW *</label>
                <input type="text" id="sale_price_in_input" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[final]" value="{{ old('price.final', $product->json['price']['final']) ?? 0.000000 }}" placeholder="Verkoopprijs" required>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group form-group-default">
                <label>Kortingsprijs met BTW</label>
                <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[discount]" value="{{ old('price.discount', $product->json['price']['discount']) ?? 0.000000 }}" placeholder="Kortingsprijs met BTW">
              </div>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="featured_image">Hoofdafbeelding</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="featured_image_input" data-preview="featured_image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="featured_image_input" name="featured_image" class="img_lfm_input form-control" accept="image/x-png" value="{{ old('featured_image', $product->json['featured_image']) }}" type="text">
                </div>
                <img id="featured_image_holder" src="{{$product->json['featured_image'] ? URL::to('/') . $product->json['featured_image'] : ''}}" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<div class="card card-transparent">
  <div class="card-header ">
    <div class="card-title">Attributen
    </div>
  </div>
</div>

<div class="card-block attributes-row">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block attributeInputContainer">
          @if(count($product->json['attributes']) == 0)
          <div class="row attribute_input_row">
            <div class="col-sm-2" style="padding-top:10px;">
              <button class="btn btn-danger btn-round removeAttributeRowButton" style="display:none;">-</button>
              <button class="btn btn-success btn-round addAttributeRowButton">+</button>
            </div>

            <div class="col-sm-4">
              <div class="form-group form-group-default">
                <label>Naam</label>
                <input type="text" class="form-control" placeholder="Naam" name="attribute_name[]">
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group form-group-default">
                <label>Prijs</label>
                <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="attribute_price[]">
              </div>
            </div>

            <div class="col-sm-4" style="padding-top:10px;">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="featured_image_x_input" data-preview="featured_image_x_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="featured_image_x_input" name="attribute_image[]" class="img_lfm_input form-control" type="text">
                </div>
                <img id="featured_image_x_holder" class="img_lfm_holder" src="" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
            <hr>
          </div>
          @else
          @foreach($product->json['attributes'] as $attribute)
          <div class="row attribute_input_row">
            <div class="col-sm-2" style="padding-top:10px;">
              <button class="btn btn-danger btn-round removeAttributeRowButton" @if(count($product->json['attributes']) == 1) style="display:none;" @endif>-</button>
              <button class="btn btn-success btn-round addAttributeRowButton">+</button>
            </div>

            <div class="col-sm-4">
              <div class="form-group form-group-default">
                <label>Naam</label>
                <input type="text" class="form-control" placeholder="Naam" name="attribute_name[]" value="{{ $attribute['name'] }}">
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group form-group-default">
                <label>Prijs</label>
                <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="attribute_price[]" value="{{ $attribute['price'] }}">
              </div>
            </div>

            <div class="col-sm-4" style="padding-top:10px;">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-btn">
                    <a id="lfm" data-input="featured_image_x_input_{{ $loop->index }}" data-preview="featured_image_x_holder_{{ $loop->index }}" class="btn btn-primary img_lfm_link" style="color:#FFF">
                      <i class="fa fa-picture-o"></i> Upload afbeelding
                    </a>
                  </span>
                  <input id="featured_image_x_input_{{ $loop->index }}" name="attribute_image[]" class="img_lfm_input form-control" type="text" value="{{ $attribute['image'] }}">
                </div>
                <img id="featured_image_x_holder_{{ $loop->index }}" class="img_lfm_holder" src="{{ $attribute['image'] ? URL::to('/').$attribute['image'] : '' }}" style="margin-top:15px;max-height:100px;">
              </div>
            </div>
            <hr>
          </div>
          @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>
</div>


<div class="card card-transparent">
  <div class="card-header ">
    <div class="card-title">Opties
    </div>
  </div>
</div>

<div class="card-block options-row">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-block optionInputContainer">
          @if( (array_key_exists('options', $product->json) && count($product->json['options']) == 0) || !array_key_exists('options', $product->json))
          <div class="row option_input_row">
            <div class="col-sm-2" style="padding-top:10px;">
              <button class="btn btn-danger btn-round removeOptionRowButton" style="display:none;">-</button>
              <button class="btn btn-success btn-round addOptionRowButton">+</button>
            </div>

            <div class="col-sm-3">
              <div class="form-group form-group-default">
                <label>Naam</label>
                <input type="text" class="form-control" placeholder="Naam" name="option_name[]">
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group form-group-default">
                <label>Type</label>
                <select class="full-width" data-init-plugin="select2" name="option_type[]" data-minimum-results-for-search="-1">
                    <option value="select">Dropdown Selectie</option>
                    <option value="radio">Radio Buttons</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group form-group-default">
                <label>Waarden</label>
                <input type="text" class="form-control" placeholder="Waarden,gescheiden,door,kommas" name="option_values[]">
              </div>
            </div>

            <hr>
          </div>
          @else
          @foreach($product->json['options'] as $option)
          <div class="row option_input_row">
            <div class="col-sm-2" style="padding-top:10px;">
              <button class="btn btn-danger btn-round removeOptionRowButton" @if(count($product->json['options']) == 1) style="display:none;" @endif>-</button>
              <button class="btn btn-success btn-round addOptionRowButton">+</button>
            </div>

            <div class="col-sm-3">
              <div class="form-group form-group-default">
                <label>Naam</label>
                <input type="text" class="form-control" placeholder="Naam" name="option_name[]" value="{{ $option['name'] }}">
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group form-group-default">
                <label>Type</label>
                <select class="full-width" data-init-plugin="select2" name="option_type[]" data-minimum-results-for-search="-1">
                    <option value="select" @if($option['type'] == 'select') selected @endif>Dropdown Selectie</option>
                    <option value="radio" @if($option['type'] == 'radio') selected @endif>Radio Buttons</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group form-group-default">
                <label>Waarden</label>
                <input type="text" class="form-control" placeholder="Waarden,gescheiden,door,kommas" name="option_values[]" value="{{ $option['values'] }}">
              </div>
            </div>

            <hr>
          </div>
          @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card card-transparent">
  <br>
  <p class="pull-right">
    <input type="hidden" name="_token" value="{{ Session::token() }}">
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <button type="submit" name="create" class="btn btn-success btn-cons pull-right" value="1">Opslaan</button>
    <a href="{{ route('dashboard.module.order_form.products.index') }}" class="pull-right"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
  </p>
</div>
</form>
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



  $('body').on('click', '.addAttributeRowButton', function (event) {
    event.preventDefault();
    console.log('help');
    $('.attribute_input_row:first').clone().appendTo('.attributeInputContainer');
    
    vardatainput = $('.attribute_input_row:last').find('.img_lfm_link').attr('data-input');
    vardatapreview = $('.attribute_input_row:last').find('.img_lfm_link').attr('data-preview');

    $('.attribute_input_row:last').find('.img_lfm_link').attr('data-input', vardatainput+'_'+$('.attribute_input_row').length);
    $('.attribute_input_row:last').find('.img_lfm_link').attr('data-preview', vardatapreview+'_'+$('.attribute_input_row').length);
    inputid = $('.attribute_input_row:last').find('.img_lfm_input').attr('id');
    $('.attribute_input_row:last').find('.img_lfm_input').attr('id',inputid+'_'+$('.attribute_input_row').length);
    holderid = $('.attribute_input_row:last').find('.img_lfm_holder').attr('id');
    $('.attribute_input_row:last').find('.img_lfm_holder').attr('id',holderid+'_'+$('.attribute_input_row').length);

    toggleRemoveButton();

    init();
  });

  $('body').on('click', '.removeAttributeRowButton', function (event) {
    event.preventDefault();
    console.log('helpmee');
    $(this).parents('.attribute_input_row').remove();

    toggleRemoveButton();
  });


  $('body').on('click', '.addOptionRowButton', function (event) {
    event.preventDefault();
    console.log('help');
    $('.option_input_row:first').clone().appendTo('.optionInputContainer');

    vardatainput = $('.option_input_row:last').find('.img_lfm_link').attr('data-input');
    vardatapreview = $('.option_input_row:last').find('.img_lfm_link').attr('data-preview');

    $('.option_input_row:last').find('.img_lfm_link').attr('data-input', vardatainput+'_'+$('.option_input_row').length);
    $('.option_input_row:last').find('.img_lfm_link').attr('data-preview', vardatapreview+'_'+$('.option_input_row').length);
    inputid = $('.option_input_row:last').find('.img_lfm_input').attr('id');
    $('.option_input_row:last').find('.img_lfm_input').attr('id',inputid+'_'+$('.option_input_row').length);
    holderid = $('.option_input_row:last').find('.img_lfm_holder').attr('id');
    $('.option_input_row:last').find('.img_lfm_holder').attr('id',holderid+'_'+$('.option_input_row').length);

    toggleRemoveOptionButton();

    init();
  });

  $('body').on('click', '.removeOptionRowButton', function (event) {
    event.preventDefault();
    console.log('helpmee');
    $(this).parents('.option_input_row').remove();

    toggleRemoveOptionButton();
  });

    

  

  function toggleRemoveButton() {
    if($('.attribute_input_row').length > 1) {
      $('.removeAttributeRowButton').show();
    } else {
      $('.removeAttributeRowButton').hide();
    }
  }

  function toggleRemoveOptionButton() {
    if($('.option_input_row').length > 1) {
      $('.removeOptionRowButton').show();
    } else {
      $('.removeOptionRowButton').hide();
    }
  }
  
});
</script>
@endsection
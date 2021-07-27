@extends('chuckcms::backend.layouts.base')

@section('content')
<div class="container p-3">
  <div class="row">
    <div class="col-sm-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.module.order_form.products.index') }}">Producten</a></li>
          <li class="breadcrumb-item active" aria-current="product">Maak een nieuw product</li>
        </ol>
      </nav>
    </div>
  </div>
  <form action="{{ route('dashboard.module.order_form.products.save') }}" method="POST">
    <div class="row">
      @if ($errors->any())
        <div class="col-sm-12">
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      @endif
      <div class="col-sm-12">
        <div class="my-3">
          <ul class="nav nav-tabs justify-content-start" id="productTab" role="tablist">
            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
              <li class="nav-item" role="presentation">
                <a class="nav-link{{ $loop->iteration == 1 ? ' active' : '' }}" id="{{ $langKey.'_page-tab' }}" data-target="#tab_product_{{ $langKey }}" data-toggle="tab" href="#" role="tab" aria-controls="#{{ $langKey.'_page' }}" aria-selected="{{ $loop->iteration == 1 ? 'true' : 'false' }}">
                  <span>{{ $langValue['name'] }} ({{ strtoupper($langKey) }})</span>
                </a>
              </li>
            @endforeach
          </ul>

          <div class="tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="productTabContent">
            @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
              <div class="col-sm-12 tab-pane fade show{{ $loop->iteration == 1 ? ' active' : '' }} tab_product_wrapper" role="tabpanel" id="tab_product_{{ $langKey }}">
                <div class="row column-seperation">
                  <div class="col-lg-12">
                    <div class="form-group form-group-default required">
                      <label>Naam *</label>
                      <input type="text" class="form-control" placeholder="Titel" name="name[{{ $langKey }}]" value="{{ old('name.'.$langKey) }}" required>
                    </div>
                    <div class="form-group form-group-default required">
                      <label>Beschrijving *</label>
                      <textarea class="form-control" placeholder="Beschrijving" name="description[{{ $langKey }}]" rows="2" style="height:50px;" required>{{ old('description.'.$langKey) }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="bg-light shadow-sm rounded p-3 mb-3 mx-1">
            <div class="col-sm-12 tab-pane fade show active" id="fade1">
              <div class="row column-separation">
                <div class="col-lg-12">
                  <div class="form-group form-group-default required ">
                    <label>Categorie</label><br>
                    <select class="custom-select w-100" name="category">
                      @foreach (ChuckRepeater::for(config('chuckcms-module-order-form.categories.slug')) as $category )
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row column-separation">
                <div class="col-sm-4">
                  <div class="form-group form-group-default input-group">
                    <div class="form-input-group">
                      <label class="inline">Wordt online weergegeven?</label>
                    </div>
                    <div class="input-group-addon bg-transparent h-c-50 pl-3">
                      <input type="hidden" name="is_displayed" value="0">
                      <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_displayed" {{ old('is_displayed') == '1' ? 'checked' : '' }} />
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group form-group-default input-group">
                    <div class="form-input-group">
                      <label class="inline">Mag online verkocht worden?</label>
                    </div>
                    <div class="input-group-addon bg-transparent h-c-50 pl-3">
                      <input type="hidden" name="is_buyable" value="0">
                      <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_buyable" {{ old('is_buyable') == '1' ? 'checked' : '' }} />
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group form-group-default input-group">
                    <div class="form-input-group">
                      <label class="inline">Beschikbaar in POS?</label>
                    </div>
                    <div class="input-group-addon bg-transparent h-c-50 pl-3">
                      <input type="hidden" name="is_pos_available" value="0">
                      <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_pos_available" {{ old('is_pos_available') == '1' ? 'checked' : '' }} />
                    </div>
                  </div>
                </div>
              </div>
              <div class="row column-separation">
                <div class="col-sm-6">
                  <div class="form-group form-group-default required">
                    <label>Verkoopprijs met BTW *</label>
                    <input type="text" id="sale_price_in_input" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[final]" value="{{ old('price.final') ?? 0.000000 }}" placeholder="Verkoopprijs" required>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group form-group-default">
                    <label>Kortingsprijs met BTW</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[discount]" value="{{ old('price.discount') ?? 0.000000 }}" placeholder="Kortingsprijs met BTW">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group form-group-default required">
                    <label>BTW-percentage voor levering *</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="price[vat_delivery]" value="{{ old('price.vat_delivery') ?? 6 }}" data-v-min="0" data-v-max="21" required>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group form-group-default required">
                    <label>BTW-percentage voor afhaal *</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="price[vat_takeout]" value="{{ old('price.vat_takeout') ?? 6 }}" data-v-min="0" data-v-max="21" required>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group form-group-default required">
                    <label>BTW-percentage voor on-the-spot *</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="price[vat_on_the_spot]" value="{{ old('price.vat_on_the_spot') ?? 12 }}" data-v-min="0" data-v-max="21" required>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Beschikbare hoeveelheid?</label> <br>
                    @foreach(ChuckRepeater::for(config('chuckcms-module-order-form.locations.slug')) as $location)
                    <label class="mt-2">Locatie: <b>{{ $location->name }}</b></label>
                    <input type="text"  data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="quantity[{{ $location->id }}]" value="{{ old('quantity.'.$location->id.'') ?? -1 }}" data-v-min="-1">
                    @endforeach
                  </div>
                </div>
              </div>
              <hr>
              <div class="row column separation">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="featured_image">Hoofdafbeelding</label>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <a id="lfm" data-input="featured_image_input" data-preview="featured_image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                          <i class="fa fa-picture-o"></i> Upload afbeelding
                        </a>
                      </span>
                      <input id="featured_image_input" name="featured_image" class="img_lfm_input form-control ml-2" accept="image/x-png" value="{{ old('featured_image') }}" type="text">
                    </div>
                    <img id="featured_image_holder" src="" style="margin-top:15px;max-height:100px;">
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-12">
                  <div class="card-title">Attributen</div>
                </div>
                <div class="col-sm-12 attributeInputContainer">
                  <div class="row attribute_input_row" style="align-items: center;">
                    <div class="col-sm-2">
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
                    <div class="col-sm-4">
                      <div class="form-group pt-5">
                        <div class="input-group">
                          <span class="input-group-btn">
                            <a id="lfm" data-input="featured_image_x_input" data-preview="featured_image_x_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                              <i class="fa fa-picture-o"></i> Upload afbeelding
                            </a>
                          </span>
                          <input id="featured_image_x_input" name="attribute_image[]" class="img_lfm_input form-control ml-2" type="text">
                        </div>
                        <img id="featured_image_x_holder" class="img_lfm_holder" src="" style="margin-top:15px;max-height:100px;">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-12">
                  <div class="card-title">Opties</div>
                </div>
                <div class="col-sm-12 optionInputContainer">
                  <div class="row option_input_row" style="align-items: center;">
                    <div class="col-sm-2">
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
                        <select class="custom-select" name="option_type[]">
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
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-12">
                  <div class="card-title">Extras</div>
                </div>
                <div class="col-sm-12">
                  <div class="extraInputContainer">
                    <div class="row extra_input_row" style="align-items: center;">
                      <div class="col-sm-2">
                        <button class="btn btn-danger btn-round removeExtraRowButton" style="display:none;">-</button>
                        <button class="btn btn-success btn-round addExtraRowButton">+</button>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <label>Naam</label>
                          <input type="text" class="form-control" placeholder="Naam" name="extra_name[]">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group form-group-default">
                          <label>Prijs</label>
                          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="extra_price[]">
                        </div>
                      </div>

                      <div class="col-sm-3 offset-sm-2">
                        <div class="form-group form-group-default required">
                          <label>BTW% voor levering *</label>
                          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="extra_vat_delivery[]" data-v-min="0" data-v-max="21" required>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group form-group-default required">
                          <label>BTW% voor afhaal *</label>
                          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="extra_vat_takeout[]" data-v-min="0" data-v-max="21" required>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group form-group-default required">
                          <label>BTW% voor on-the-spot *</label>
                          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="extra_vat_on_the_spot[]" data-v-min="0" data-v-max="21" required>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <hr>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="my-3">
          <p class="pull-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="submit" name="create" class="btn btn-success btn-cons pull-right mx-2" value="1">Opslaan</button>
            <a href="{{ route('dashboard.module.order_form.products.index') }}" class="pull-right mx-2"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
          </p>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('css')
@endsection

@section('scripts')
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script src="//cdn.chuck.be/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
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

  $('body').on('click', '.addAttributeRowButton', function (event) {
    event.preventDefault();
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
    $(this).parents('.attribute_input_row').remove();

    toggleRemoveButton();
  });



  $('body').on('click', '.addOptionRowButton', function (event) {
    event.preventDefault();
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
    $(this).parents('.option_input_row').remove();

    toggleRemoveOptionButton();
  });


  $('body').on('click', '.addExtraRowButton', function (event) {
    event.preventDefault();
    $('.extra_input_row:first').clone().appendTo('.extraInputContainer');

    vardatainput = $('.extra_input_row:last').find('.img_lfm_link').attr('data-input');
    vardatapreview = $('.extra_input_row:last').find('.img_lfm_link').attr('data-preview');

    $('.extra_input_row:last').find('.img_lfm_link').attr('data-input', vardatainput+'_'+$('.extra_input_row').length);
    $('.extra_input_row:last').find('.img_lfm_link').attr('data-preview', vardatapreview+'_'+$('.extra_input_row').length);
    inputid = $('.extra_input_row:last').find('.img_lfm_input').attr('id');
    $('.extra_input_row:last').find('.img_lfm_input').attr('id',inputid+'_'+$('.extra_input_row').length);
    holderid = $('.extra_input_row:last').find('.img_lfm_holder').attr('id');
    $('.extra_input_row:last').find('.img_lfm_holder').attr('id',holderid+'_'+$('.extra_input_row').length);

    toggleRemoveExtraButton();

    init();
  });

  $('body').on('click', '.removeExtraRowButton', function (event) {
    event.preventDefault();
    $(this).parents('.extra_input_row').remove();

    toggleRemoveExtraButton();
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

  function toggleRemoveExtraButton() {
    if($('.extra_input_row').length > 1) {
      $('.removeExtraRowButton').show();
    } else {
      $('.removeExtraRowButton').hide();
    }
  }
	
});
</script>
@endsection
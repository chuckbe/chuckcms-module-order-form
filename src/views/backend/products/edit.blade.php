@extends('chuckcms::backend.layouts.base')

@section('content')
<div class="container p-3">
	<div class="row">
    	<div class="col-sm-12">
      		<nav aria-label="breadcrumb">
        		<ol class="breadcrumb mt-3">
          			<li class="breadcrumb-item"><a href="{{ route('dashboard.module.order_form.products.index') }}">Producten</a></li>
          			<li class="breadcrumb-item active" aria-current="product">Bewerk huidig product</li>
        		</ol>
      		</nav>
    	</div>
  	</div>
  	{{-- {{dd(Chuckbe\Chuckcms\Models\Module::where('slug', 'chuckcms-module-order-form')->firstOrFail()->json['admin']['settings']['categories'])}} --}}
  	<form action="{{ route('dashboard.module.order_form.products.update') }}" method="POST">
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
							<div class="col-sm-12 tab-pane fade show{{ $loop->iteration == 1 ? ' active' : '' }}" role="tabpanel" id="tab_product_{{ $langKey }}">
								<div class="row column-seperation">
									<div class="col-lg-12">
										<div class="form-group form-group-default required">
											<label>Naam *</label>
											<input type="text" class="form-control" placeholder="Titel" name="name[{{ $langKey }}]" value="{{ old('name.'.$langKey, $product->json['name'][$langKey]) }}" required>
										</div>
										<div class="form-group form-group-default required">
											<label>Beschrijving *</label>
											<textarea class="form-control" placeholder="Beschrijving" name="description[{{ $langKey }}]" rows="2" style="height:50px;" required>{{ old('description.'.$langKey, $product->json['description'][$langKey]) }}</textarea>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
					<div class="bg-light shadow-sm rounded p-3 mb-3 mx-1">
						<div class="tab-pane fade show active" id="fade1">
							<div class="row column-separation">
								<div class="col-lg-12">
									<div class="form-group form-group-default required ">
										<label>Categorie</label>
										<select class="custom-select w-100" name="category">
											@foreach (ChuckRepeater::for(config('chuckcms-module-order-form.categories.slug')) as $category )
												<option value="{{ $category->id }}" @if($category->id == $product->category) selected @endif >{{ $category->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="row column-separation">
								<div class="col-sm-4">
									<div class="form-group form-group-default input-group">
										<div class="form-input-group">
											<label class="inline">Wordt weergegeven?</label>
										</div>
										<div class="input-group-addon bg-transparent h-c-50 px-3">
											<input type="hidden" name="is_displayed" value="0">
											<input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_displayed" {{ old('is_displayed', $product->json['is_displayed']) == '1' ? 'checked' : '' }} />
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group form-group-default input-group">
										<div class="form-input-group">
											<label class="inline">Mag verkocht worden?</label>
										</div>
										<div class="input-group-addon bg-transparent h-c-50 px-2">
											<input type="hidden" name="is_buyable" value="0">
											<input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_buyable" {{ old('is_buyable', $product->json['is_buyable']) == '1' ? 'checked' : '' }} />
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group form-group-default input-group">
										<div class="form-input-group">
											<label class="inline">Beschikbaar in POS?</label>
										</div>
										<div class="input-group-addon bg-transparent h-c-50 px-2">
											<input type="hidden" name="is_pos_available" value="0">
											<input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="is_pos_available" {{ old('is_pos_available', $product->is_pos_available) == '1' ? 'checked' : '' }} />
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row column-separation">
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
								<div class="col-sm-4">
									<div class="form-group form-group-default required">
										<label>BTW-percentage voor levering *</label>
										<input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="price[vat_delivery]" value="{{ old('price.vat_delivery', $product->getJson('price.vat_delivery')) ?? 6 }}" data-v-min="0" data-v-max="21" required>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group form-group-default required">
										<label>BTW-percentage voor afhaal *</label>
										<input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="price[vat_takeout]" value="{{ old('price.vat_takeout', $product->getJson('price.vat_takeout')) ?? 6 }}" data-v-min="0" data-v-max="21" required>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group form-group-default required">
										<label>BTW-percentage voor on-the-spot *</label>
										<input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="price[vat_on_the_spot]" value="{{ old('price.vat_on_the_spot', $product->getJson('price.vat_on_the_spot')) ?? 12 }}" data-v-min="0" data-v-max="21" required>
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
											<input type="text"  data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="quantity[{{ $location->id }}]" value="{{ old('quantity.'.$location->id.'', $product->getJson('quantity.'.$location->id.'')) ?? -1 }}" data-v-min="-1">
										@endforeach
									</div>
								</div>
							</div>
							<hr>
							<div class="row column-separation">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="featured_image">Hoofdafbeelding</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a id="lfm" data-input="featured_image_input" data-preview="featured_image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
													<i class="fa fa-picture-o"></i> Upload afbeelding
												</a>
											</span>
											<input id="featured_image_input" name="featured_image" class="img_lfm_input form-control ml-2" accept="image/x-png" value="{{ old('featured_image', $product->json['featured_image']) }}" type="text">
										</div>
										<img id="featured_image_holder" src="{{$product->json['featured_image'] ? URL::to('/') . $product->json['featured_image'] : ''}}" style="margin-top:15px;max-height:100px;">
									</div>
								</div>
							</div>
							<hr>
							<div class="row column-separation">
								<div class="col-sm-12">
									<div class="card-title"><h6><b>Attributen</b></h6></div>
								</div>
								<div class="col-sm-12 attributes-row">
									<div class="attributeInputContainer">
										@if(count($product->json['attributes']) == 0)
											<div class="row attribute_input_row" style="align-items: center;">
												<div class="col-sm-2">
													<button class="btn btn-danger btn-round removeAttributeRowButton" style="display:none;">-</button>
													<button class="btn btn-success btn-round addAttributeRowButton">+</button>
												</div>
												<div class="col-§ col-sm-4">
													<div class="form-group form-group-default">
														<label>Naam</label>
														<input type="text" class="form-control" placeholder="Naam" name="attribute_name[]">
													</div>
												</div>
												<div class="col-6 col-sm-2">
													<div class="form-group form-group-default">
														<label>Prijs</label>
														<input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="attribute_price[]">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group pt-0 pt-sm-5">
														<div class="input-group">
															<span class="input-group-btn mx-1">
																<a id="lfm" data-input="featured_image_x_input" data-preview="featured_image_x_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
																	<i class="fa fa-picture-o"></i>
																</a>
                              								</span>
                              								<input id="featured_image_x_input" name="attribute_image[]" class="img_lfm_input form-control" type="text">
                            							</div>
                            							<img id="featured_image_x_holder" class="img_lfm_holder" src="" style="margin-top:15px;max-height:100px;">
                          							</div>
                        						</div>
                      						</div>
                      					@else
                        					@foreach($product->json['attributes'] as $attribute)
                          						<div class="row attribute_input_row" style="align-items: center;">
                            						<div class="col-sm-2">
                              							<button class="btn btn-danger btn-round removeAttributeRowButton" @if(count($product->json['attributes']) == 1) style="display:none;" @endif>-</button>
                              							<button class="btn btn-success btn-round addAttributeRowButton">+</button>
                            						</div>
                            						<div class="col-6 col-sm-4">
                              							<div class="form-group form-group-default">
                                							<label>Naam</label>
                                							<input type="text" class="form-control" placeholder="Naam" name="attribute_name[]" value="{{ $attribute['name'] }}">
                              							</div>
                            						</div>
                            						<div class="col-6 col-sm-2">
                              							<div class="form-group form-group-default">
                                							<label>Prijs</label>
                                							<input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="attribute_price[]" value="{{ $attribute['price'] }}">
                              							</div>
                            						</div>
                            						<div class="col-sm-4">
                              							<div class="form-group pt-0 pt-sm-5">
                                							<div class="input-group">
                                  								<span class="input-group-btn mx-1">
                                    								<a id="lfm" data-input="featured_image_x_input_{{ $loop->index }}" data-preview="featured_image_x_holder_{{ $loop->index }}" class="btn btn-primary img_lfm_link" style="color:#FFF">
                                      									<i class="fa fa-picture-o"></i>
                                    								</a>
                                  								</span>
                                  								<input id="featured_image_x_input_{{ $loop->index }}" name="attribute_image[]" class="img_lfm_input form-control" type="text" value="{{ $attribute['image'] }}">
                                							</div>
                                							<img id="featured_image_x_holder_{{ $loop->index }}" class="img_lfm_holder" src="{{ $attribute['image'] ? URL::to('/').$attribute['image'] : '' }}" style="margin-top:15px;max-height:100px;">
                              							</div>
                            						</div>
                          						</div>
                        					@endforeach
                    					@endif
                  					</div>
                				</div>
              				</div>
              				<hr>
              				<div class="row column-separation">
                				<div class="col-sm-12">
                  					<div class="card-title"><h6><b>Opties</b></h6></div>
                				</div>
                				<div class="col-sm-12 options-row">
                  					<div class="optionInputContainer">
                    					@if( (array_key_exists('options', $product->json) && count($product->json['options']) == 0) || !array_key_exists('options', $product->json))
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
														<select class="full-width form-control select2" data-init-plugin="select2" name="option_type[]" data-minimum-results-for-search="-1">
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
										@else
                        					@foreach($product->json['options'] as $option)
                          						<div class="row option_input_row" style="align-items: center;">
                            						<div class="col-sm-2">
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
															<select class="custom-select" name="option_type[]">
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
												</div>
											@endforeach
										@endif
									</div>
								</div>
							</div>
							<hr>
							<div class="row column-separation">
								<div class="col-sm-12">
									<div class="card-title"><h6><b>Extras</b></h6></div>
								</div>
								<div class="col-sm-12 extras-row">
									<div class="extraInputContainer">
										@if( (array_key_exists('extras', $product->json) && count($product->json['extras']) == 0) || !array_key_exists('extras', $product->json))
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
											</div>
										@else
											@foreach($product->json['extras'] as $extra)
												<div class="row extra_input_row" style="align-items: center;">
													<div class="col-sm-2">
														<button class="btn btn-danger btn-round removeExtraRowButton" @if(count($product->json['options']) == 1) style="display:none;" @endif>-</button>
														<button class="btn btn-success btn-round addExtraRowButton">+</button>
													</div>
													<div class="col-sm-6">
														<div class="form-group form-group-default">
															<label>Naam</label>
															<input type="text" class="form-control" placeholder="Naam" name="extra_name[]" value="{{ $extra['name'] }}">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group form-group-default">
															<label>Prijs</label>
															<input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="extra_price[]" value="{{ $extra['price'] }}">
														</div>
													</div>
													<div class="col-sm-3 offset-sm-2">
														<div class="form-group form-group-default required">
															<label>BTW% voor levering *</label>
															<input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="extra_vat_delivery[]" value="{{ array_key_exists('vat_delivery', $extra) ? $extra['vat_delivery'] : 6 }}" data-v-min="0" data-v-max="21" required>
														</div>
													</div>
													<div class="col-sm-3">
														<div class="form-group form-group-default required">
															<label>BTW% voor afhaal *</label>
															<input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="extra_vat_takeout[]" value="{{ array_key_exists('vat_takeout', $extra) ? $extra['vat_takeout'] : 6 }}" data-v-min="0" data-v-max="21" required>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group form-group-default required">
															<label>BTW% voor on-the-spot *</label>
															<input type="text" data-a-dec="." data-a-sep="" data-m-dec="0" data-a-pad=true class="autonumeric form-control" name="extra_vat_on_the_spot[]" value="{{ array_key_exists('vat_on_the_spot', $extra) ? $extra['vat_on_the_spot'] : 12 }}" data-v-min="0" data-v-max="21" required>
														</div>
													</div>
													<div class="col-sm-12">
														<hr>
													</div>
												</div>
											@endforeach
										@endif
									</div>
								</div>
							</div>
							<div class="row column-separation">
								<div class="col-sm-12">
									<div class="card-title">
										<h6 class="d-inline"><b>Sub products</b></h6>
										<button class="btn btn-sm btn-outline-secondary float-right" 
											role="button" id="addNewSubproductsGroupBtn">
											<small>+ groep</small>
										</button>
									</div>
								</div>
								<div class="col-sm-12 subproducts_group_container">
									@if((array_key_exists('subproducts', $product->json) && count($product->json['subproducts']) == 0) || !array_key_exists('subproducts', $product->json))
										<div class="subproducts_wrapper _input_container" data-group="1">
											<hr>
											<div class="form-group row mb-3">
												<div class="col-sm-4">
													<label>Group Naam</label>
													<input type="text" class="form-control subproductgroupname" placeholder="Naam" name="subproducts[1][name]">
												</div>
												<div class="col-sm-4">
													<label>Group Label</label>
													<input type="text" class="form-control subproductgrouplabel" placeholder="Label" name="subproducts[1][label]">
												</div>
												<div class="col-sm-2">
													<label>Minimale waarde</label>
													<input type="number" class="form-control subproductgroupmin" min="1" name="subproducts[1][min]">
												</div>
												<div class="col-sm-2">
													<label>Maximale waarde</label>
													<input type="number" class="form-control subproductgroupmax" min="1" name="subproducts[1][max]">
												</div>
												<div class="col-12 pt-3">
													<small class="d-inline-block float-right mr-2 mt-2"><button class="btn btn-sm btn-outline-primary add_subproduct_btn" role="button"><small>+ products</small></button></small>
													<small class="d-inline-block float-right mr-2 mt-2">
														<button class="btn btn-sm btn-outline-danger remove_subproducts_group_btn d-none" role="button">
															<small>Verwijder groep</small>
														</button>
													</small>
												</div>
											</div>
											<div class="form-group row subproducts_input_line _input_line d-none">
												<div class="col-6 col-sm-3">
													<label class="sr-only">Product naam *</label>
													<div class="input-group input-group-sm">
														<div class="input-group-prepend">
															<button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
														</div>
														<select class="custom-select subproduct_input" name="subproducts[1][products][]" disabled required>
															@foreach ($subproducts as $subproduct)
																<option value="{{$subproduct->id}}" data-productid="{{$subproduct->id}}">{{$subproduct->json['name']['nl']}}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-12 col-sm-6">
													<label class="sr-only">extra prijs *</label>
													<input type="text" class="form-control h-auto subproducts_extra_price" style="padding-top: 3px; padding-bottom: 3px;" placeholder="€0,00" value="0" aria-label="extraprijs" aria-describedby="extra prijs" readonly required>
												</div>
											</div>
										</div>
									@else
										@foreach ($product->json['subproducts'] as $subproduct)
											@php $group_id = ($loop->index) + 1;  @endphp
											<div class="subproducts_wrapper _input_container" data-group="{{$group_id}}">
												<hr>
												<div class="form-group row mb-3">
													<div class="col-sm-4">
														<label>Group Naam</label>
														<input type="text" class="form-control subproductgroupname" value="{{$subproduct['name']}}" placeholder="Naam" name="subproducts[{{$group_id}}][name]">
													</div>
													<div class="col-sm-4">
														<label>Group Label</label>
														<input type="text" class="form-control subproductgrouplabel" value="{{$subproduct['label']}}" placeholder="Label" name="subproducts[{{$group_id}}][label]">
													</div>
													<div class="col-sm-2">
														<label>Minimale waarde</label>
														<input type="number" class="form-control subproductgroupmin" value="{{$subproduct['min']}}" min="1" name="subproducts[{{$group_id}}][min]">
													</div>
													<div class="col-sm-2">
														<label>Maximale waarde</label>
														<input type="number" class="form-control subproductgroupmax" value="{{$subproduct['max']}}" min="1" name="subproducts[{{$group_id}}][max]">
													</div>
													<div class="col-12 pt-3">
														<small class="d-inline-block float-right mr-2 mt-2"><button class="btn btn-sm btn-outline-primary add_subproduct_btn" role="button"><small>+ products</small></button></small>
														<small class="d-inline-block float-right mr-2 mt-2">
															<button class="btn btn-sm btn-outline-danger remove_subproducts_group_btn{{ $group_id > 1 ? '' : ' d-none' }}" role="button">
																<small>Verwijder groep</small>
															</button>
														</small>
													</div>
												</div>
												@foreach ($subproduct['products'] as $inputProduct)
													<div class="form-group row subproducts_input_line _input_line">
														<div class="col-6 col-sm-3">
															<label class="sr-only">Product naam *</label>
															<div class="input-group input-group-sm">
																<div class="input-group-prepend">
																	<button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
																</div>
																<select class="custom-select subproduct_input" name="subproducts[{{$group_id}}][products][{{$inputProduct['id']}}][id]" required>
																	@foreach ($subproducts as $subproduct)
																		<option value="{{$subproduct->id}}" data-productid="{{$subproduct->id}}"{{$subproduct->id == $inputProduct['id'] ? ' selected' : ''}}>{{$subproduct->json['name']['nl']}}</option>
																	@endforeach
																</select>
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<label class="sr-only">extra prijs *</label>
															<input type="text" class="form-control h-auto subproducts_extra_price" style="padding-top: 3px; padding-bottom: 3px;" value="{{$inputProduct['extra_price']}}" aria-label="extraprijs" aria-describedby="extra prijs" readonly required name="subproducts[{{$group_id}}][products][{{$inputProduct['id']}}][extra_price]">
														</div>
													</div>
												@endforeach
											</div>
										@endforeach
									@endif
								</div>
								<div class="col-sm-12">
									<div class="row d-none" id="addNewSubproductWrapper">
										<div class="col-sm-6">
											<label for="new_subproduct">Product naam:</label>
											<select id="new_subproduct" class="subproduct_selector custom-select custom-select-sm" data-element-selector="#new_subproduct_value" class="custom-select">
												@foreach ($subproducts as $subproduct)
													<option value="{{$subproduct->id}}" data-productid="{{$subproduct->id}}">{{$subproduct->json['name']['nl']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-sm-3">
											<label for="new_subproduct_extra_price">Extra prijs:</label>
											<input type="text" id="new_subproduct_extra_price" class="form-control h-auto" style="padding-top: 3px; padding-bottom: 3px;"  value="0" aria-label="extraprijs" aria-describedby="extra prijs" required>
										</div>
										<div class="col-sm-3 pt-4">
											<button type="button" class="btn btn-sm btn-outline-success" id="new_subproduct_button" data-group="1"><small>+ subproduct toevoegen</small></button>
											<div class="w-100 d-block"></div>
											<small class="d-none text-danger" id="new_subproduct_error">Vul alle velden in</small>
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
						<input type="hidden" name="product_id" value="{{ $product->id }}">
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
$(document).ready(function() {
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
$('body').on('click', '#addNewSubproductsGroupBtn', function (event) {
	event.preventDefault()
	
	$('.subproducts_wrapper:first').clone().appendTo('.subproducts_group_container');
    
	if ($('.subproducts_wrapper').length > 1) {
		$('.subproducts_wrapper:last').attr('data-group', $('.subproducts_wrapper').length);
		$('.subproducts_wrapper:last').find('.subproductgroupname').attr('name', 'subproducts['+$('.subproducts_wrapper').length+'][name]');
		$('.subproducts_wrapper:last').find('.subproductgrouplabel').attr('name', 'subproducts['+$('.subproducts_wrapper').length+'][label]');
		$('.subproducts_wrapper:last').find('.subproductgroupmin').attr('name', 'subproducts['+$('.subproducts_wrapper').length+'][min]');
		$('.subproducts_wrapper:last').find('.subproductgroupmax').attr('name', 'subproducts['+$('.subproducts_wrapper').length+'][max]');
		$('.subproducts_wrapper:last').find('.subproductgroupname').val('');
		$('.subproducts_wrapper:last').find('.subproductgrouplabel').val('');
		$('.subproducts_wrapper:last').find('.subproductgroupmin').val('1');
		$('.subproducts_wrapper:last').find('.subproductgroupmax').val('1');
		$('.subproducts_wrapper:last').find('.remove_subproducts_group_btn').removeClass('d-none');
		$('.subproducts_wrapper:last').find('.remove_line_button').trigger('click');
		$('.subproducts_wrapper:last').find('.subproducts_input_line:first').remove();
	}
});
$('body').on('click', '.remove_subproducts_group_btn', function (event) {
	event.preventDefault()
	
	if($('.subproducts_wrapper').length > 1) {
		$(this).parents('.subproducts_wrapper').remove();
		$('#new_subproduct_button').attr('data-group', '0');
		$('#addNewSubproductWrapper').addClass('d-none');
	}
});

$('body').on('click', '.add_subproduct_btn', function (event) {
	event.preventDefault();
	
	let group_id = $(this).parents('.subproducts_wrapper').attr('data-group');
	
	$('#new_subproduct_button').attr('data-group', group_id);
	$('#new_subproduct').prop("selectedIndex", 0);
	$('#new_subproduct_extra_price').val('0');
	$('#addNewSubproductWrapper').removeClass('d-none');
});

$('body').on('click', '.remove_line_button', function(event) {
	event.preventDefault();

	let checker = $(this).parents('._input_container').find('._input_line').length;
	let group_id = $(this).parents('.conditions_wrapper').attr('data-group');

	if(checker > 1 || group_id !== '1') {
		$(this).parents('._input_line').remove();
	} else {
		$(this).parents('._input_line').addClass('d-none');
		$(this).parents('._input_line').find('select').prop('disabled', true);
	}
});

$('body').on('click', '#new_subproduct_button', function(event) {
	event.preventDefault();

	let group_id = $(this).attr('data-group');

	$('#new_subproduct_error').addClass('d-none');

    if ($('.subproducts_wrapper[data-group="'+group_id+'"]').length == 0) {
		group_id = $('.conditions_wrapper:first').attr('data-group');
	}

	if($('#new_subproduct').find('option:selected').first().val().length == 0 || $('#new_subproduct_extra_price').val().length === 0) {
		$('#new_subproduct_error').removeClass('d-none');
		return;
	}

	new_subproduct = $('#new_subproduct').find('option:selected').first().val();
	new_extra_price = $('#new_subproduct_extra_price').val();


	if(group_id == '1' && $('.subproducts_input_line:first').hasClass('d-none')) {
		$('.subproducts_input_line:first').removeClass('d-none');
		$('.subproducts_input_line:first').find('select').prop('disabled', false);
	} else {
		$('.subproducts_input_line:first').clone().appendTo('.subproducts_wrapper[data-group="'+group_id+'"]');
		$('.subproducts_wrapper[data-group="'+group_id+'"]')
			.find('.subproducts_input_line:last')
			.removeClass('d-none');
		$('.subproducts_wrapper[data-group="'+group_id+'"]')
			.find('.subproducts_input_line:last')
			.find('select').prop('disabled', false);
	}


    $('.subproducts_wrapper[data-group="'+group_id+'"]')
        .find('.subproducts_input_line:last')
        .find('.subproduct_input').first()
        .prop('name', 'subproducts['+group_id+'][products]['+new_subproduct+'][id]');

    $('.subproducts_wrapper[data-group="'+group_id+'"]')
		.find('.subproducts_input_line:last')
		.find('.subproduct_input').first()
        .find('option:not([data-productid="'+new_subproduct+'"])').prop('selected', false).prop('disabled', true);
        
    $('.subproducts_wrapper[data-group="'+group_id+'"]')
		.find('.subproducts_input_line:last')
		.find('.subproduct_input').first()
		.find('option[value="'+new_subproduct+'"]').first().prop('disabled', false).prop('selected', true);


    $('.subproducts_wrapper[data-group="'+group_id+'"]')
		.find('.subproducts_input_line:last')
		.find('.subproducts_extra_price').first()
		.prop('name', 'subproducts['+group_id+'][products]['+new_subproduct+'][extra_price]');
        
	$('.subproducts_wrapper[data-group="'+group_id+'"]')
		.find('.subproducts_input_line:last')
		.find('.subproducts_extra_price').first().val(new_extra_price)

	$(this).attr('data-group', '0');
	$('#addNewSubproductWrapper').addClass('d-none');
});

</script>
@endsection
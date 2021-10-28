<div class="menuItemArea row">
    <div class="container pl-4 pr-4 pl-md-5 pr-md-5 d-flex">
        <div class="tab-content" id="navigationTabContent">
            @php $c = 0; @endphp
            @foreach($categories as $category)
            @if($category->is_pos_available == true)
            <div class="tab-pane fade show{{ $c == 0 ? ' active' : '' }}" id="category{{$category->id}}Tab" role="tabpanel" aria-labelledby="category{{$category->id}}Tab">
                <div class="row">
                    

                    @foreach($products as $product)
                    @if($product->json['category'] == $category->id && $product->is_pos_available == true)
                    <div class="col-6 col-sm-4 col-md-3 p-1 cof_pos_product_card {{-- unavailable --}}" data-product-id="{{ $product->id }}" data-product-category-id="{{ $product->getJson('category') }}" data-product-name="{{ $product->getJson('name.nl') }}" data-q="{{ http_build_query($product->getJson('quantity'),'',',') }}" data-vat-delivery="{{ $product->json['price']['vat_delivery'] }}" data-vat-takeout="{{ $product->json['price']['vat_takeout'] }}" data-vat-on-the-spot="{{ $product->json['price']['vat_on_the_spot'] }}" data-current-price="{{ $product->json['price']['discount'] !== '0.000000' ? $product->json['price']['discount'] : $product->json['price']['final'] }}" data-product-attributes="{{ json_encode($product->json['attributes']) }}" data-product-options="{{ json_encode($product->json['options']) }}" @if(array_key_exists('extras', $product->json)) data-product-extras="{{ json_encode($product->json['extras']) }}" @endif>
                        <div class="card shadow-sm">
                            <div class="card-body py-3 px-4">
                                <p class="card-title mb-2" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><small><b>{{ $product->getJson('name.nl') }}</b></small></p>
                                <div class="row">
                                    <div class="col">
                                        <small class="d-block card-subtitle mb-1 text-muted cof_productItemPriceDisplay" data-product-id="{{ $product->id }}" data-current-price="{{ $product->json['price']['discount'] !== '0.000000' ? $product->json['price']['discount'] : $product->json['price']['final'] }}">
                                            <span class="cof_productItemUnitPrice" data-product-id="{{ $product->id }}" data-product-price="{{ $product->json['price']['final'] }}" data-has-discount="{{ $product->json['price']['discount'] == '0.000000' ? 'false' : 'true' }}" @if($product->json['price']['discount'] !== '0.000000') style="text-decoration:line-through" @endif>{{ '€ ' . number_format($product->json['price']['final'], 2, ',', '.') }}</span> 
                                            @if($product->json['price']['discount'] !== '0.000000')
                                            <span style="color:red;" class="cof_productItemDiscountPrice" data-product-id="{{ $product->id }}" data-discount-price="{{ $product->json['price']['discount'] }}">{{ '€ ' . number_format($product->json['price']['discount'], 2, ',', '.') }}</span>
                                            @endif

                                        </small>
                                        {{-- <h6 class="card-subtitle mb-2 text-muted">€ ${parseFloat(product.json.price.final).toFixed(2).replace(".", ",")}</h6>
                                         ${(product.json.quantity[ogLocation] == 0) ? '<p style="font-size: 10px; color: #e72870">Niet beschikbaar</p>' : ''} --}}
                                    </div>
                                    {{-- <div class="col">
                                        <img src=${featured_img} class="img-fluid" alt=${product.json.name.nl}>
                                    </div> --}}

                                    {{-- <div class="{{ count($product->json['attributes']) > 0 ? 'col-sm-12' : 'col-sm-12' }} d-none">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary cof_subtractionProductBtn" data-product-id="{{ $product->id }}">-</button>
                                            </div>
                                            <input type="number" min="0" max="99" step="1" value="1" class="form-control cof_productQuantityInput" data-product-id="{{ $product->id }}" readonly style="text-align:center;padding: 0.375rem 0.5rem;">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary cof_additionProductBtn" data-product-id="{{ $product->id }}" data-q="{{ http_build_query($product->getJson('quantity'),'',',') }}">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    @if(count($product->json['attributes']) > 0)
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <select name="" id="" class="custom-select form-control cof_attributeSelectInput" data-product-id="{{ $product->id }}">
                                                <option value="" selected="true" disabled="disabled" data-option-is="false">—— Maak een keuze ——</option>
                                                @foreach($product->json['attributes'] as $attribute)
                                                <option value="{{ $attribute['name'] }}" data-attribute-name="{{ $attribute['name'] }}" data-attribute-img="{{ $attribute['image'] !== null ? URL::to('/') . $attribute['image'] : ($product->json['featured_image'] ?? 'https://via.placeholder.com/500x333.jpg?text=No+Image+Found') }}" data-attribute-price="{{ $attribute['price'] }}" data-product-id="{{ $product->id }}">{{ $attribute['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                @if(array_key_exists('options', $product->json) && count($product->json['options']) > 0)
                                                <button class="btn btn-outline-primary cof_btnAddProductAttributeOptionsToCart" data-product-id="{{ $product->id }}" data-product-options="{{ json_encode($product->json['options']) }}" @if(array_key_exists('extras', $product->json)) data-product-extras="{{ json_encode($product->json['extras']) }}" @endif>Toevoegen</button>
                                                @else
                                                <button class="btn btn-outline-primary cof_btnAddProductAttributeToCart" data-product-id="{{ $product->id }}">Toevoegen</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            @if(array_key_exists('options', $product->json) && count($product->json['options']) > 0 || array_key_exists('extras', $product->json) && count($product->json['extras']) > 0 )
                                            <button class="btn btn-outline-primary btn-block cof_btnAddProductOptionsToCart" data-product-id="{{ $product->id }}" data-product-options="{{ json_encode($product->json['options']) }}" @if(array_key_exists('extras', $product->json)) data-product-extras="{{ json_encode($product->json['extras']) }}" @endif>Toevoegen</button>
                                            @else
                                            <button class="btn btn-outline-primary btn-block cof_btnAddProductToCart" data-product-id="{{ $product->id }}">Toevoegen</button>
                                            @endif
                                        </div>
                                    </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach



                    
                </div>
            </div>
            @php $c++; @endphp
            @endif
            @endforeach
        </div>
        
    </div>
</div>
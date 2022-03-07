<div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="" id="options-form">
            <div class="modal-header">
                <h5 class="modal-title font-cera-bold" id="optionsModalLabel">Selecteer de opties voor: <span class="options_product_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="attributesModalBody">
                    <div class="row attributes_modal_row">
                        <div class="col-sm-12 mb-3">
                            <label class="d-block">Kies attribuut</label>
                            <div class="btn-group-horizontal btn-group-toggle attributes_modal_item_button_group" data-toggle="buttons">
                                <label class="btn btn-secondary mr-2 mb-3 attributes_modal_item_button">
                                    <input type="radio" name="attributes" id="option1"> <span class="attributes_modal_item_button_text">Active</span>
                                </label>
                                <label class="btn btn-secondary attributes_modal_item_button">
                                    <input type="radio" name="attributes" id="option2"> Radio
                                </label>
                                <label class="btn btn-secondary attributes_modal_item_button">
                                    <input type="radio" name="attributes" id="option3"> Radio
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="optionsModalBody">
                    <div class="row options_modal_row">
                        
                        <div class="col-sm-12 options_modal_item_radio">
                        	<label for="" class="options_item_name">Radio</label>
                            <div class="form-group cof_options_radio_item_input_group mb-2">
                                <div class="form-check cof_options_radio_item_input">
    								<label class="form-check-label" for="exampleRadios1">
    								<input class="form-check-input" type="radio" name="cof_options_radio" id="exampleRadios1" value="option1">
    								<span> Default radio</span>
    								</label>
    							</div>
                            </div>
                        </div>

                        <div class="col-sm-12 options_modal_item_select">
                            <div class="form-group">
                                <label for="cofOptionsSelect" class="options_item_name">Select</label>
                                <select name="cof_options_select" class="custom-select cof_options_select_item_input" required>
                                    <option value="default" class="cof_options_option_input">Default</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="extrasModalBody">
                    <div class="row extras_modal_row">
                        <div class="col-sm-12 extras_modal_item">
                            <div class="form-check cof_extras_checkbox_item_input">
                                <input class="form-check-input extras_item_checkbox" type="checkbox" value="" id="defaultCheck1">
                                <label class="form-check-label extras_item_name" for="defaultCheck1">
                                Default checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-block" id="addProductFromModalToCartButton">Toevoegen</button>
            </div>
        </form>
    </div>
  </div>
</div>



<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      {{-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Afrekenen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> --}}
      <div class="modal-body">
        <div class="row d-none">
            {{-- <div class="col-4 text-center">
                <div class="m-auto rounded-circle p-3" style="
                        width: 100px;
                        height: 100px;
                        background: #f7f7f7;">
                        <img src="{{asset('chuckbe/chuckcms-module-order-form/qr-scan.svg')}}" class="img-fluid w-100 p-2">
                </div>
                <button class="btn btn-primary my-3">QR code</button>
            </div> --}}
            <div class="col-6 text-center">
                <div class="m-auto rounded-circle p-3" style="
                    width: 100px;
                    height: 100px;
                    background: #f7f7f7;">
                    <img src="{{asset('chuckbe/chuckcms-module-order-form/wallet.svg')}}" class="img-fluid w-100 p-2">
                </div>
                <button class="btn btn-primary my-3">Cash</button>
            </div>
            <div class="col-6 text-center">
                <div class="m-auto rounded-circle p-3" style="
                    width: 100px;
                    height: 100px;
                    background: #f7f7f7;">
                    <img src="{{asset('chuckbe/chuckcms-module-order-form/credit-card.svg')}}" class="img-fluid w-100 p-2">
                </div>
                <button class="btn btn-primary my-3">Kaartbetaling</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <h5 class="mt-3 mb-5">Voer betaling uit...</h5>
            </div>
            <div class="col-12">
                <label>Cash</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                    <input type="text" class="form-control numpad cof_checkoutCashInput" placeholder="0.00">
                    <div class="input-group-append">
                        <button class="input-group-text cof_checkoutCashPaymentReset">⌫</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="5">+€5</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="10">+€10</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="20">+€20</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="50">+€50</button>
                        <button class="input-group-text cof_checkoutCashFitPayment">Gepast</button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label>Kaart</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                    <input type="text" class="form-control numpad cof_checkoutCardInput" placeholder="0.00">
                    <div class="input-group-append">
                        <button class="input-group-text cof_checkoutCardPaymentReset">⌫</button>
                        <button class="input-group-text cof_checkoutCardFitPayment">Gepast</button>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center">
                <h5 class="mt-3 mb-2">Resterend bedrag</h5>
                <p class="cof_checkoutPendingAmount">0,00</p>
            </div>

            <div class="col-12">
                <hr>
                <button class="btn btn-secondary" id="cof_cancelOrderBtn">Annuleren</button>
                <button class="btn btn-success float-right" id="cof_finalizeOrderBtn" disabled>Bestelling voltooien</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerModalLabel">Selecteer klant</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <label>Selecteer een klant uit de lijst</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text" id="cof_customerSelectDefaultGuest">Guest</div>
                    </div>
                    <select id="cof_customerSelectInput" class="custom-select">
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" class="cof_customerSelectInputOption" data-is-guest="{{ $customer->guest ? 'true' : 'false' }}" data-customer-email="{{ $customer->email }}" data-ean="{{ $customer->ean }}" data-points="{{ $customer->loyalty_points }}" data-coupons="{{ $customer->coupons->toJson() }}">{{ $customer->surname.' '.$customer->name.' ('.$customer->email.')' }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <hr>
                <button class="btn btn-secondary" id="cof_cancelSelectClientBtn">Annuleren</button>
                <button class="btn btn-success float-right" id="cof_selectCustomerForCartBtn">Selecteer klant</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="couponsModal" tabindex="-1" role="dialog" aria-labelledby="couponsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="couponsModalLabel">Selecteer coupon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <label>Selecteer een coupon uit de lijst</label>
                <div class="btn-group-horizontal btn-group-toggle" data-toggle="buttons">
                    @foreach(ChuckRepeater::for(config('chuckcms-module-order-form.discounts.slug')) as $discount)
                    @if($discount->type !== 'gift' && $discount->active && \Carbon\Carbon::parse($discount->valid_from) < \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))) && \Carbon\Carbon::parse($discount->valid_until) > \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))))
                    <label class="btn btn-secondary mr-2 mb-3">
                        <input type="radio" name="coupon_selector" value="{{ $discount->id }}" data-name="{{ $discount->name }}" data-active="{{ $discount->active }}" data-valid-from="{{ \Carbon\Carbon::parse($discount->valid_from)->timestamp }}" data-valid-until="{{ \Carbon\Carbon::parse($discount->valid_until)->timestamp }}" data-customers="{{ is_array($discount->customers) ? implode(',', $discount->customers) : '' }}" data-minimum="{{ (int)$discount->minimum }}" data-available-total="{{ (int)$discount->available_total }}" data-available-customer="{{ (int)$discount->available_customer }}" data-conditions="{{ json_encode($discount->conditions) }}" data-discount-type="{{ $discount->type }}" data-discount-value="{{ $discount->value }}" data-apply-on="{{ $discount->apply_on }}" data-apply-product="{{ $discount->apply_product }}" data-uncompatible-discounts="{{ is_array($discount->uncompatible_discounts) ? implode(',', $discount->uncompatible_discounts) : '' }}" data-remove-incompatible="{{ $discount->remove_incompatible }}"> <span>{{ $discount->name.($discount->remove_incompatible ? '*' : '') }}</span>
                    </label>
                    @else
                    <label class="btn btn-secondary mr-2 mb-3 d-none">
                        <input type="radio" name="coupon_selector" value="{{ $discount->id }}" data-name="{{ $discount->name }}" data-active="{{ $discount->active }}" data-valid-from="{{ \Carbon\Carbon::parse($discount->valid_from)->timestamp }}" data-valid-until="{{ \Carbon\Carbon::parse($discount->valid_until)->timestamp }}" data-customers="{{ is_array($discount->customers) ? implode(',', $discount->customers) : '' }}" data-minimum="{{ (int)$discount->minimum }}" data-available-total="{{ (int)$discount->available_total }}" data-available-customer="{{ (int)$discount->available_customer }}" data-conditions="{{ json_encode($discount->conditions) }}" data-discount-type="{{ $discount->type }}" data-discount-value="{{ $discount->value }}" data-apply-on="{{ $discount->apply_on }}" data-apply-product="{{ $discount->apply_product }}" data-uncompatible-discounts="{{ is_array($discount->uncompatible_discounts) ? implode(',', $discount->uncompatible_discounts) : '' }}" data-remove-incompatible="{{ $discount->remove_incompatible }}" disabled> <span>{{ $discount->name.($discount->remove_incompatible ? '*' : '') }}</span>
                    </label>
                    @endif
                    @endforeach
                </div>
                <div class="mt-3">
                    <small>* Incompatibele coupons zullen automatisch verwijderd worden</small>
                </div>
            </div>

            <div class="col-12">
                <hr>
                <small class="d-block text-danger text-right mb-2 d-none" id="cof_couponErrorText"></small>
                <div class="w-100 d-block"></div>
                <button class="btn btn-secondary" id="cof_cancelSelectCouponBtn">Annuleren</button>
                <button class="btn btn-success float-right" id="cof_addSelectedCouponToCartBtn">Coupon Toevoegen</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div role="alert" aria-live="assertive" aria-atomic="true"  class="toast text-success" id="customerChangedToast" style="position: absolute; bottom: 25px; left: 25px;">
    <div class="toast-header">
      <strong class="mr-auto"><b>SCANNER</b></strong>
      <small>nu</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Klant werd succesvol gewijzigd!
    </div>
</div>

<div role="alert" aria-live="assertive" aria-atomic="true"  class="toast text-danger" id="couponAlreadyInCartToast" style="position: absolute; bottom: 125px; left: 25px;">
    <div class="toast-header">
      <strong class="mr-auto"><b>COUPON</b></strong>
      <small>nu</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Coupon is reeds in gebruik!
    </div>
</div>

<div role="alert" aria-live="assertive" aria-atomic="true"  class="toast text-success" id="couponAddedToCartToast" style="position: absolute; bottom: 125px; left: 25px;">
    <div class="toast-header">
      <strong class="mr-auto"><b>COUPON</b></strong>
      <small>nu</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Coupon werd toegevoegd!
    </div>
</div>



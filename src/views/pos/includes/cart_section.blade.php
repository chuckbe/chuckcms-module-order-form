<div class="bestelHeader row align-items-center">
    <div class="col-6 text-left h-100">
        <h4 class="bestelHeaderTitle">Bestelling</h4>
    </div>
    <div class="col-6 text-right bestelHeaderInstellingen h-100">
        <button type="button" class="btn shadow-sm deletealles">Verwijder alles</button>
        <button type="button" class="btn shadow-sm"><i class="fas fa-cog"></i></button>
    </div>
</div>
<div class="bestelTabHandler row">
    <nav class="nav nav-pills flex-column flex-sm-row cof_cartTabList" id="bestelNavigationTab" role="tablist">       
        <a class="flex-sm-fill text-sm-center nav-link" id="cof_cartTabListNewOrderLink" href="#">
            <span><i class="fas fa-plus"></i><span>
        </a>
        <a class="cof_cartTabListLink flex-sm-fill text-sm-center nav-link active" id="cart_0123Tab" data-cart-id="cart_0123" href="#cart_0123" role="tab" data-toggle="tab" aria-controls="cart_0123Tab" aria-selected="true">
            <span>Cart: #1 (<span class="cof_cartTotalQuanity" data-cof-quantity="0">0</span>)</span>
            <span class="remove-tab cof_cartTabRemove"><i class="fas fa-times-circle"></i></span>
        </a>
    </nav>
</div>
<div class="bestelTabArea row">
    <div class="tab-content" id="bestelNavigationTabContent">
        <div class="cof_cartTab tab-pane fade show active" id="cart_0123"  role="tabpanel" aria-labelledby="cart_0123Tab" data-cart-id="cart_0123">

            <div class="cof_CartProductList" style="display:none;">
                <div class="bestelOrder cof_cartProductListItem row align-items-center" data-product-id="0" data-product-name="" data-attribute-name="" data-quantity="0" data-unit-price="0" data-total-price="0">
                    <div class="col-5 bestelOrderDetails">
                        <div class="bestelOrderTitle cof_cartProductListDetails">
                            <span class="cof_cartProductListItemFullName">Product Naam</span>
                            <small class="text-muted d-block"><span class="cof_cartProductListItemQuantity">1</span> x <span class="cof_cartProductListItemUnitPrice">€ 0,00</span></small>
                            <small class="text-muted d-none cof_cartProductListItemOptions">
                              <span class="cof_cartProductListItemOptionName">Optie 1</span>: <span class="cof_cartProductListItemOptionValue">Waarde</span>
                            </small>
                            <small class="text-muted d-none cof_cartProductListItemExtras">
                              <span class="cof_cartProductListItemOptionName">Optie 1</span> <span class="cof_cartProductListItemOptionValue">Waarde</span>
                            </small>
                        </div> 
                    </div>
                    <div class="col-4 bestelOrderQuantity">
                        <div class="bestelOrderQuantityControl trash cof_cartProductListItemSubtraction">
                            <div class="cof_deleteProductFromListButton" style="cursor:pointer;">
                                <i class="fas fa-trash"></i>
                                {{-- ${(product.quantity > 1) ? '<i class="fas fa-minus"></i>': '<i class="fas fa-trash"></i>'} --}}
                            </div>
                        </div>
                        <input type="text" class="cof_cartProductListItemQuantity" name="quantity" readonly value="1">
                        <div class="bestelOrderQuantityControl cof_cartProductListItemAddition">
                            <div class="addbtn"><i class="fas fa-plus"></i></div>
                        </div>
                    </div>
                    <div class="col-3 bestelOrderPrice cof_cartProductListItemTotalPrice">
                      € 100,95
                    </div>
              </div>

            </div>


            
          
          {{-- <ul class="list-group mb-3" id="cof_CartProductList" style="display:none;">
            <li class="list-group-item d-flex justify-content-start lh-condensed cof_cartProductListItem" data-product-id="0" data-product-name="" data-attribute-name="" data-quantity="0" data-unit-price="0" data-total-price="0">
              <div style="padding: 7px 15px 7px 0px;">
                <img src="{{ asset('chuckbe/chuckcms-module-order-form/trash-solid.svg') }}" class="cof_deleteProductFromListButton" height="12" width="12" alt="Verwijder product" style="cursor:pointer;">
              </div>
              <div class="flex-fill cof_cartProductListDetails">
                <h6 class="my-0 cof_cartProductListItemFullName">Product name</h6>
                <small class="text-muted d-block"><span class="cof_cartProductListItemQuantity">1</span> x <span class="cof_cartProductListItemUnitPrice">€ 0,00</span></small>
                <small class="text-muted d-none cof_cartProductListItemOptions">
                  <span class="cof_cartProductListItemOptionName">Optie 1</span>: <span class="cof_cartProductListItemOptionValue">Waarde</span>
                </small>
                <small class="text-muted d-none cof_cartProductListItemExtras">
                  <span class="cof_cartProductListItemOptionName">Optie 1</span> <span class="cof_cartProductListItemOptionValue">Waarde</span>
                </small>
              </div>
              <span class="text-muted cof_cartProductListItemTotalPrice">€ 0,00</span>
            </li>
            <li class="list-group-item d-flex justify-content-between" id="cof_CartProductListShippingLine">
              <span>Verzending (EUR)</span>
              <strong class="cof_cartShippingPrice">€ 0,00</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between" id="cof_CartProductListPriceLine">
              <span>Totaal (EUR)</span>
              <strong class="cof_cartTotalPrice">€ 0,00</strong>
            </li>
          </ul> --}}

        </div>
    </div>
</div>
<div class="klantArea row">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
              <div class="row">
                  <div class="col-7 klantDetails">
                      <div class="col-2 klantIcon">
                        <i class="fas fa-user-circle"></i>
                      </div>
                      <div class="col-10 klantGegevens">
                          <p>Klant:</p>
                          <p>guest@guest.com</p>
                      </div>
                  </div>
                  <div class="col-5 klantKoppeler">
                    <button class="btn w-100">Ontkoppelen</button>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
<div class="priceCalculatorArea row">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="subtotaal row">
                  <div class="col-6 text-left">Subtotaal</div>
                  <div class="col-6 text-right st-value">€ 0,00</div>
                </div>
                <div class="korting row">
                    <div class="col-6 text-left">Korting</div>
                    <div class="col-6 text-right ko-value">€ 0,00</div>
                </div>
                <div class="btw row">
                    <div class="col-6 text-left">BTW</div>
                    <div class="col-6 text-right btw-value">€ 0,00</div>
                </div>
                <hr class="priceCalculatorDivider"/>
                <div class="totaal row">
                    <div class="col-6 text-left">Totaal</div>
                    <div class="col-6 text-right tot-value cof_cartTotalPrice">€ 0,00</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="betaalArea row">
    <div class="container">
        <button class="btn text-center d-block " id="cof_placeOrderBtnNow">Betalen</button>
    </div>
</div>
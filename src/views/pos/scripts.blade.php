<script>
    var clientPrinters = null;
    var _this = this;

    //WebSocket settings
    JSPM.JSPrintManager.auto_reconnect = true;
    JSPM.JSPrintManager.start();
    JSPM.JSPrintManager.WS.onStatusChanged = function () {
        if (jspmWSStatus()) {
            //get client installed printers
            JSPM.JSPrintManager.getPrinters().then(function (printersList) {
                clientPrinters = printersList;
                var options = '';
                for (var i = 0; i < clientPrinters.length; i++) {
                    options += '<option>' + clientPrinters[i] + '</option>';
                }
                $('#printerName').html(options);
            });
        }
    };

    //Check JSPM WebSocket status
    function jspmWSStatus() {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
            return true;
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
            console.warn('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
            alert('JSPM has blocked this website!');
            return false;
        }
    }

    //Do printing...
    function doPrinting() {
        if (jspmWSStatus()) {

            // Gen sample label featuring logo/image, barcode, QRCode, text, etc by using JSESCPOSBuilder.js

            var escpos = Neodynamic.JSESCPOSBuilder;
            var doc = new escpos.Document();
            escpos.ESCPOSImage.load('data:image/png;base64,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')
            .then(logo => {

                // logo image loaded, create ESC/POS commands

                var escposCommands = doc
                    .setCharacterCodeTable(19)
                    .align(escpos.TextAlignment.Center)
                    .image(logo, escpos.BitmapDensity.D24)
                    .feed(2)
                    .font(escpos.FontFamily.A)
                    .align(escpos.TextAlignment.Center)
                    .style([escpos.FontStyle.Bold])
                    .size(0, 0)
                    .text("DONUTTELLO")
                    .font(escpos.FontFamily.B)
                    .size(0, 0)
                    .text("Bergstraat 27,")
                    .text("2220 Heist-op-den-Berg")
                    .feed(2)
                    .font(escpos.FontFamily.A)
                    .size(0, 0)
                    .text("KASTICKET")
                    .feed(2)
                    
                    .drawLine()
                    .drawTable(["2 Classic Donut", "     3.00 C"])
                    .drawTable(["1 Boxdeal: Doosje van 6", "    15.00 C"])
                    .drawTable(["1 2 euro deal:", "     2.00  "])
                    .drawTable(["  1 Classic Donut", "     1.00 C"])
                    .drawTable(["  1 Basic Koffie", "     1.00 C"])
                    .text("3 Filled Donut                            9.60 C")
                    .drawLine()
                    
                    .font(escpos.FontFamily.B)
                    .style([escpos.FontStyle.Bold])
                    .size(1, 1)
                    .drawTable(["Totaal", "€ 230.00"])

                    .feed(2)
                    .font(escpos.FontFamily.A)
                    .linearBarcode('1234567', escpos.Barcode1DType.EAN8, new escpos.Barcode1DOptions(2, 100, true, escpos.BarcodeTextPosition.Below, escpos.BarcodeFont.A))
                    .qrCode('https://donuttello.com', new escpos.BarcodeQROptions(escpos.QRLevel.L, 6))
                    .pdf417('PDF417 data to be encoded here', new escpos.BarcodePDF417Options(3, 3, 0, 0.1, false))
                    .feed(5)
                    .cut()
                    .generateUInt8Array();


                // create ClientPrintJob
                var cpj = new JSPM.ClientPrintJob();

                // Set Printer info
                var myPrinter = new JSPM.InstalledPrinter($('#printerName').val());
                cpj.clientPrinter = myPrinter;

                // Set the ESC/POS commands
                cpj.binaryPrinterCommands = escposCommands;

                // Send print job to printer!
                cpj.sendToClient();

            });
        }
    }
</script>
<script>
$(document).ready(function() {
    $.fn.numpad.defaults.gridTpl = '<table class="table bg-white"></table>';
    $.fn.numpad.defaults.backgroundTpl = '<div class=""></div>';
    $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" />';
    $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-default"></button>';
    $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn" style="width: 100%;"></button>';
    $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.done').addClass('btn-primary');};

$('input.numpad').numpad({decimalSeparator: '.'});

var cof_pos_active_location = $('#cof_pos_location').attr('data-active-location');
var cof_pos_active_location_type = $('#cof_pos_location').attr('data-type');
var cof_pos_processing_order = false;
init();

$('body').on('click', '.locationDropdownSelect', function (event) {
    event.preventDefault();

    changeActiveLocation($(this));
});

$('body').on('change', '#locationTypeSwitcher', function (event) {
    event.preventDefault();

    changeActiveLocationType();
});

$('body').on('click', '#cof_cartTabListNewOrderLink', function (event) {
    event.preventDefault();

    cartId = getNewCartId();
    addNewCartTabLink(cartId);
    addNewCartTab(cartId);
    storeNewCart(cartId);
    activateCart(cartId);
});

$('body').on('click', '.cof_cartTabListLink', function (event) {
    cart_id = $(this).attr('data-cart-id');
    activateCart(cart_id);
});

$('body').on('click', '.cof_pos_product_card', function (event) {
    event.preventDefault();
    product_id = $(this).attr('data-product-id');

    if (productNeedsModal(product_id)) {
        addProductToModal($(this));
        return;
    }

    product = getProductDetails(product_id)
    addToCart(product);    
});

$('body').on('click', '#addProductFromModalToCartButton', function (event) {
    event.preventDefault();
    product_id = $(this).attr('data-product-id');
    
    product = getProductDetailsFromModal(product_id);
    addToCart(product);

    $('#optionsModal').modal('hide');
    return;
});

$('body').on('click', '.cof_cartProductListItemAddition', function (event) {
    event.preventDefault();
    
    let cart_id = getActiveCart();
    let product = getProductDetailsFromCartItemElement(cart_id, $(this), false);

    addToCart(product);
});

$('body').on('click', '.cof_cartProductListItemSubtraction', function (event) {
    event.preventDefault();
    
    let cart_id = getActiveCart();
    let product = getProductDetailsFromCartItemElement(cart_id, $(this), -1);

    addToCart(product);
});

$('body').on('click', '.cof_cartTabRemove', function (event) {
    event.preventDefault();
    event.stopPropagation();

    cart_id = $(this).parent().attr('data-cart-id');
    removeCart(cart_id);
    
});

$('body').on('click', '#cof_selectCustomerAccount', function (event) {
    event.preventDefault();

    $('#customerModal').modal('show');
});

$('body').on('click', '#cof_selectCustomerForCartBtn', function (event) {
    event.preventDefault();

    cart_id = getActiveCart();
    customer_id = getSelectedCustomer();

    updateCustomerForCart(customer_id, cart_id);

    $('#customerModal').modal('hide');
});

$('body').on('click', '#openCouponsModal', function (event) {
    event.preventDefault();

    $('#couponsModal').modal('show');
});

$('body').on('click', '#cof_addSelectedCouponToCartBtn', function (event) {
    event.preventDefault();

    cart_id = getActiveCart();
    addSelectedCouponToCart(cart_id);
});

$('body').on('click', '#cof_cancelSelectCouponBtn', function (event) {
    event.preventDefault();

    closeCouponModal();
});

$('body').on('click', '.cof_cartCouponItemRemoveBtn', function (event) {
    event.preventDefault();

    cart_id = getActiveCart();
    removeCouponFromCart($(this).parent().attr('data-coupon'), cart_id);
});

    



$('body').on('click', '#options-form .options_modal_item_radio label.form-check-label', function (event) {
    $(this).siblings('input').first().prop('checked', true);
});

//to-do: betalen button
$('body').on('click', '.betaalArea #cof_placeOrderBtnNow', function (event) {
    event.preventDefault();
    openPaymentModal(getActiveCart());
});

$('body').on('click', '.cof_checkoutCashFitPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    cartId = getActiveCart();
    $('.cof_checkoutCashInput').val(getTotalPrice(cartId));
    $('.cof_checkoutCashInput').trigger('change');

});

$('body').on('click', '.cof_checkoutCardFitPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }
    
    cartId = getActiveCart();
    $('.cof_checkoutCardInput').val(getTotalPrice(cartId));
    $('.cof_checkoutCardInput').trigger('change');

});

$('body').on('click', '.cof_checkoutCashAddPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }
    
    let newCashAmount = ( Number($('.cof_checkoutCashInput').val()) + parseInt($(this).attr('data-amount')) );
    $('.cof_checkoutCashInput').val(newCashAmount);
    $('.cof_checkoutCashInput').trigger('change');  
});

$('body').on('click', '.cof_checkoutCashPaymentReset', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }
    
    $('.cof_checkoutCashInput').val('0.00');
    $('.cof_checkoutCashInput').trigger('change');    
});

$('body').on('click', '.cof_checkoutCardPaymentReset', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }
    
    $('.cof_checkoutCardInput').val('0.00');
    $('.cof_checkoutCardInput').trigger('change');
});

$('body').on('change', '.cof_checkoutCashInput,.cof_checkoutCardInput', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }
    
    let paidByCard = $('.cof_checkoutCardInput').val();
    let paidByCash = $('.cof_checkoutCashInput').val();

    let total_tethered = Number(paidByCard) + Number(paidByCash);

    let cartId = getActiveCart();

    let pendingAmount = getTotalPrice(cartId) - total_tethered;

    $('.cof_checkoutPendingAmount').text(formatPrice(pendingAmount));

    if(pendingAmount <= 0) {
        $('#cof_finalizeOrderBtn').prop('disabled', false);
    } else {
        $('#cof_finalizeOrderBtn').prop('disabled', true);
    }
});

$('body').on('click', '#cof_cancelOrderBtn', function (event) {
    event.preventDefault();

    cof_pos_processing_order = false;

    $('#cof_finalizeOrderBtn').prop('disabled', true);
    $('#cof_finalizeOrderBtn').html('Bestelling voltooien');

    $('#paymentModal').modal('hide');
});

$('body').on('click', '#cof_finalizeOrderBtn', function (event) {
    event.preventDefault();

    cof_pos_processing_order = true;

    $(this).prop('disabled', true);
    $(this).html('<i class="fa fa-sync-alt fa-spin"></i> Verwerken... ');

    let cartId = getActiveCart();

    if(validateCartForOrder(cartId)) {
        placeOrderFromCart(cartId)
    }
});


$('body').on('click', '#openPrintSettingsModal', function (event) {
    event.preventDefault();

    $('#printerSettingsModal').modal('show');
});



/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
function init() {
    restoreCartsFromStorage();

    calculateProductAvailability();
}
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */








/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
function changeActiveLocation(elem) {
    let locationId = elem.attr('data-location-id');
    let locationType = elem.attr('data-location-type');
    let locationOnTheSpot = elem.attr('data-on-the-spot');
    let locationName = elem.text();
    $('#cof_pos_location').attr('data-active-location', locationId);
    $('#cof_pos_location').attr('data-location-type', locationType);
    $('#cof_pos_location').attr('data-on-the-spot', locationOnTheSpot);
    $('#cof_pos_location').text(locationName);
    cof_pos_active_location = locationId;

    if(locationType == 'delivery') {
        $('.locationTypeSwitcherWrapper').addClass('d-none');
    } else {
        $('.locationTypeSwitcherWrapper').removeClass('d-none');
    }
    
    $('#locationTypeSwitcher').prop('checked', false);

    if(locationOnTheSpot == '1') {
        $('#locationTypeSwitcher').prop('disabled', false);
    } else {
        $('#locationTypeSwitcher').prop('disabled', true);
    }

    changeActiveLocationType();
}

function changeActiveLocationType() {
    let locationType = $('#cof_pos_location').attr('data-type');
    let locationOnTheSpot = $('#cof_pos_location').attr('data-on-the-spot');
    if(locationType == 'delivery') {
        cof_pos_active_location_type = locationType; 
    } else {
        cof_pos_active_location_type = locationOnTheSpot == '1' ? ($('#locationTypeSwitcher').is(':checked') ? 'on-the-spot' : 'takeout') : 'takeout';
    }

    calculateProductAvailability();
    calculateTotalPrice(getActiveCart());
}

function getActiveLocationType() {
    return cof_pos_active_location_type;
}
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */








/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
function addNewCartTabLink(cartId) {

    $('.cof_cartTabListLink:first').clone().appendTo('.cof_cartTabList:first').insertAfter($('#cof_cartTabListNewOrderLink'));
    $('.cof_cartTabListLink:not(:first)').removeClass('active');
    $('.cof_cartTabListLink:not(:first)').attr('aria-selected', false);
    
    $('.cof_cartTabListLink:first').attr('id', 'cof_cart_'+cartId+'_Tab');
    $('.cof_cartTabListLink:first').attr('data-cart-id', cartId);
    $('.cof_cartTabListLink:first').attr('href', '#cof_cart_'+cartId+'_');
    $('.cof_cartTabListLink:first').attr('aria-controls', 'cof_cart_'+cartId+'_Tab');

    $('.cof_cartTabListLink:first').find('span:first').html('Cart: #'+cartId+' (<span class="cof_cartTotalQuanity" data-cof-quantity="0">0</span>)')
}

function addNewCartTab(cartId) {
    $('.cof_cartTab:last').clone().appendTo('#bestelNavigationTabContent');
    $('.cof_cartTab:not(:last)').removeClass('show').removeClass('active');
    $('.cof_cartTab:last').addClass('show').addClass('active');

    $('.cof_cartTab:last').attr('id', 'cof_cart_'+cartId+'_');
    $('.cof_cartTab:last').attr('aria-labelledby', 'cof_cart_'+cartId+'_Tab');
    $('.cof_cartTab:last').attr('data-cart-id', cartId);

    $('#cof_selectedCustomerEmail').text(getCustomerEmail(getGuestCustomer()));

    resetCartTab(cartId);

    carts = getAllCartsFromStorage();
}

function removeCart(cartId) {
    let carts = getAllCartsFromStorage();

    if (carts.length > 1) {
        removeCartTab(cartId);
        removeCartFromStorage(cartId);
        $('.cof_cartTabListLink:first').trigger('click'); 
    } else if(carts.length == 1) {
        removeCartFromStorage(cartId);
        restoreCartsFromStorage();
    }
}

function removeCartTab(cartId) {
    $('.cof_cartTabListLink[data-cart-id='+cartId+']').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').remove();
}

function removeCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if( carts[i].id == cartId) {
            carts.splice(i, 1);
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            return;
        }
    };
}

function activateCart(cartId) {
    calculateTotalPrice(cartId);
    displayDiscountsForCart(cartId);
    deactivateAllCartsBut(cartId);
    $('#cof_selectedCustomerEmail').text(getCustomerEmail(getCustomerForCartFromStorage(cartId)));
}

function deactivateAllCartsBut(cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        carts[i].active = false;    
        if( carts[i].id == cartId ) {
            carts[i].active = true;
        }
    };

    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

function getAllCartsFromStorage() {
    if(localStorage.getItem('cof_carts') === null) {
        carts = [];
        localStorage.setItem('cof_carts', JSON.stringify(carts));
    } else {
        carts = JSON.parse(localStorage.getItem('cof_carts'));
    }

    return carts;
}

function getCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            return carts[g];
        }
    } 
}

function getNewCartId() {
    if(localStorage.getItem('cof_carts_daily_count') === null) {
        carts_count = 1;
    } else {
        carts_count = parseInt(JSON.parse(localStorage.getItem('cof_carts_daily_count'))) + 1;
    }
    
    localStorage.setItem('cof_carts_daily_count', JSON.stringify(carts_count));

    return carts_count;
}

function getActiveCart() {
    cart_id = $('.cof_cartTabListLink.active').attr('data-cart-id');
    return cart_id;
    // carts = getAllCartsFromStorage();
    // cartObject = [];
    // carts.each((cart)=>{
    //     if(cart.id == cart_id) {
    //         cartObject = cart;
    //     }
    // });

    // return cartObject;
}

function addToCart(product, cartId = null) {
    product_id = product.id
    cart_id = cartId === null ? getActiveCart() : cartId;

    cart_count = cartCount(cart_id);

    if (isCartEmpty(cart_id)) {
        $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_CartProductList').show();

        selector = '.cof_cartProductListItem:first';
        updateProductListItemAttributes(cart_id, selector, product_id, product.name, product.attribute, JSON.stringify(product.options), JSON.stringify(product.extras), product.quantity, product.current_price, product.total_price);

        if (cartId === null) {
            addProductToCartInStorage(product, cart_id);
        }
    } else if (cartHasProduct(cart_id, product)) {

        selector = cartProductListItemUniqueSelector(cart_id, product);
        selector = ".cof_cartProductListItem[data-product-id="+product_id+"][data-unique-el="+selector+"]";
        status = updateProductListItemQuantity(cart_id, selector, product.quantity, product.total_price);

        if (cartId === null && status == 'removed') {
            removeProductToCartInStorage(product, cart_id);
        } else if (cartId === null && status == 'updated') {
            updateProductToCartInStorage(product, cart_id);
        }
    } else {

        $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem:first').clone()
        .appendTo($('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_CartProductList'));

        selector = '.cof_cartProductListItem:last';
        
        updateProductListItemAttributes(
            cart_id, 
            selector, 
            product_id, 
            product.name, 
            product.attribute, 
            JSON.stringify(product.options), 
            JSON.stringify(product.extras), 
            product.quantity, 
            product.current_price, 
            product.total_price);

        if (cartId === null) {
            addProductToCartInStorage(product, cart_id);
        }

        $('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));
        $('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
    }

    updateCartCount(cart_id, (cart_count + parseInt(product.quantity)))

    //$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(product.quantity));
    //$('#cof_cartTotalQuanity').text(cart_count + parseInt(product.quantity));

    //reset original product tile qty input to 1
    $('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);

}

function addProductToCartInStorage(product, cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cartId) {
            products = carts[i].products;
            products.push({
                id: product.id,
                name: product.name,
                attribute: product.attribute,
                options: product.options,
                extras: product.extras,
                quantity: product.quantity,
                current_price: product.current_price,
                total_price: product.total_price,
                vat: product.vat
            });

            carts[i].products = products;
            // carts[i].total = carts[i].total + product.total_price;
            // carts[i].vat = getCartTotalVatFromProducts(products); 
            // carts[i].subtotal = (carts[i].total - carts[i].vat);
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            calculateTotalPrice(cartId)
            return;
        }
    };
}

function removeProductToCartInStorage(product, cartId) {
    let carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            
            let products = carts[g].products;

            for (var i = 0; i < products.length; i++) {
                if (products[i].id == product.id && products[i].attribute == product.attribute && JSON.stringify(products[i].options) == JSON.stringify(product.options) && JSON.stringify(products[i].extras) == JSON.stringify(product.extras) ) {
                    productIndex = i;
                    productPrice = product.total_price;
                }
            };

            products.splice(productIndex, 1);

            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            resetCouponsForCart(cartId);
            calculateTotalPrice(cartId);
            return;
        }
    };
}

function updateProductToCartInStorage(product, cartId) {
    let carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            total_price = 0;
            //vat_total_price = 0;
            let products = carts[g].products;

            for (var i = 0; i < products.length; i++) {
                if (products[i].id == product.id && products[i].attribute == product.attribute && JSON.stringify(products[i].options) == JSON.stringify(product.options) && JSON.stringify(products[i].extras) == JSON.stringify(product.extras) ) {
                    products[i].quantity = parseInt(products[i].quantity) + parseInt(product.quantity);
                    products[i].total_price = parseFloat(products[i].total_price) + parseFloat(product.total_price);
                }
                total_price = total_price + products[i].total_price;
            };
            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            calculateTotalPrice(cartId);
            return;
        }
    };
}

function cartCount(cart_id) {
    // carts = getAllCartsFromStorage();
    // cart_count = 0;
    // carts.each((cart)=>{
    //     if(cart.id == cart_id) {
    //         cart_count = cart.count;
    //     }
    // });

    // return parseInt(cart_count);
    return parseInt($('.cof_cartTabListLink[data-cart-id='+cart_id+']').find('.cof_cartTotalQuanity').attr('data-cof-quantity'));
}

function updateCartCount(cart_id, cart_count) {
    $('.cof_cartTabListLink[data-cart-id='+cart_id+']').find('.cof_cartTotalQuanity').attr('data-cof-quantity', cart_count);
    $('.cof_cartTabListLink[data-cart-id='+cart_id+']').find('.cof_cartTotalQuanity').text(cart_count);
}

function isCartEmpty(cart_id) {
    if (cartCount(cart_id) > 0) {
        return false;
    }

    return true;
}

function cartHasProduct(cart_id, product) {
    checker = false;

    if (!$('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem[data-product-id='+product.id+']').length > 0) {
        return checker;
    }
    product_extras_json = JSON.stringify(product.extras) == '[]' ? '' : JSON.stringify(product.extras);
    product_options_json = JSON.stringify(product.options) == '[]' ? '' : JSON.stringify(product.options);
    
    $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem[data-product-id='+product.id+']').each(function() {
        if(product.attribute == $(this).attr('data-attribute-name') && ""+product_options_json+"" == $(this).attr('data-product-options') && ""+product_extras_json+"" == $(this).attr('data-product-extras')) {
            checker = true;
            return true;
        }
    });

    return checker;
}

function cartProductListItemUniqueSelector(cart_id, product) {
    random = false;

    product_extras_json = JSON.stringify(product.extras) == '[]' ? '' : JSON.stringify(product.extras);
    product_options_json = JSON.stringify(product.options) == '[]' ? '' : JSON.stringify(product.options);

    $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem[data-product-id='+product.id+']').each(function() {
        if(product.attribute == $(this).attr('data-attribute-name') && ""+product_options_json+"" == $(this).attr('data-product-options') && ""+product_extras_json+"" == $(this).attr('data-product-extras')) {
            random = Math.random().toString(36).substr(2, 5);
            $(this).attr('data-unique-el', random);
            return false;
        }
    });

    return random;
}

function resetCartTab(cartId) {
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first')

    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-product-id', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-quantity', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-product-name', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-attribute-name', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-product-options', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-unit-price', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-total-price', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-unique-el', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemExtras:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemExtras:first').removeClass('d-block').addClass('d-none');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_CartProductList').hide();
}

function removeProductFromCart(cart_id, selector) {
    productListItem = $('.cof_cartTab[data-cart-id='+cart_id+']').find(''+selector+'');


    if($('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem').length > 1) {
        //more than one item in the list so remove the correct element
        product_quantity = productListItem.attr('data-quantity');
        productListItem.remove();

        //subtract product qty from the cart qty and update Cart Count
        cart_count = cartCount(cart_id);
        new_count = cart_count - parseInt(product_quantity);
        updateCartCount(cart_id, new_count);
        
    } else if( $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem').length == 1) {
        //reset the one item in the cart so it's blank
        resetCartTab(cart_id);
        
        //update the cart count to zero
        updateCartCount(cart_id, 0);
        
    }

    calculateTotalPrice(cart_id);
}

function restoreCartsFromStorage() {
    let carts = getAllCartsFromStorage();
    og_cart_id = $('.cof_cartTabListLink:first').attr('data-cart-id');
    active_cart_id = undefined;

    for (var i = 0; i < carts.length; i++) {
        cartId = carts[i].id;
        addNewCartTabLink(cartId);
        addNewCartTab(cartId);
        //activateCart(cartId);
        addProductsToCartFromStorage(cartId);
        addCustomerToCartFromStorage(cartId);
        //activateCart(cartId);

        if(carts[i].active) {
            active_cart_id = carts[i].id;
        }
    };

    

    if(carts.length == 0) {
        cartId = getNewCartId();
        addNewCartTabLink(cartId);
        addNewCartTab(cartId);
        storeNewCart(cartId);
        active_cart_id = cartId;
        //$('.cof_cartTabListLink[data-cart-id='+cartId+']').trigger('click');
        //activateCart(cartId);
    }

    removeCartTab(og_cart_id);

    if(active_cart_id !== undefined) {
        $('.cof_cartTabListLink[data-cart-id='+active_cart_id+']').trigger('click');
        activateCart(active_cart_id);
    }
}

function addProductsToCartFromStorage(cartId) {
    products = getProductsForCartFromStorage(cartId);

    for (var i = 0; i < products.length; i++) {
        addToCart(products[i], cartId);
    };
}








/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
function addCustomerToCartFromStorage(cartId) {
    customer_id = getCustomerForCartFromStorage(cartId);
    $('#cof_selectedCustomerEmail').text(getCustomerEmail(customer_id));
}

function getProductsForCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();
    products = [];

    for (var i = 0; i < carts.length; i++) {
        if (carts[i].id == cartId) {
            products = carts[i].products;
        }
    };

    return products;
}

function getCustomerForCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();
    customer_id = '';

    for (var i = 0; i < carts.length; i++) {
        if (carts[i].id == cartId) {
            customer_id = carts[i].customer_id;
        }
    };

    return customer_id;
}

function getSelectedCustomer() {
    return $('.cof_customerSelectInputOption:selected').first().val();
}

function getGuestCustomer() {
    return parseInt($('#cof_selectCustomerAccount').attr('data-guest'));
}

function getCustomerEmail(customer_id) {
    customer_email = '';
    $('.cof_customerSelectInputOption').each(function () {
        if ($(this).val() == customer_id) {
            customer_email = $(this).attr('data-customer-email');
        }
    });

    return customer_email;
}

function updateCustomerForCart(customer_id, cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cartId) {
            carts[i].customer_id = parseInt(customer_id);
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            $('#cof_selectedCustomerEmail').text(getCustomerEmail(customer_id));
            resetCouponsForCart(cartId);
            return;
        }
    };
}
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */








/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
function storeNewCart(cartId) {
    let carts = getAllCartsFromStorage();

    cart = {
        id: cartId,
        active: true,
        customer_id: parseInt(getGuestCustomer()),
        products: [],
        discounts: [],
        subtotal: 0,
        discount: 0,
        vat: 0,
        total: 0
    }

    carts.push(cart);
    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

function validateCartForOrder(cartId) {
    return true;
    // check if internet is available
    // check if printer is available
    // check if customer is selected
    // check if order has value?
}

function placeOrderFromCart(cartId) {
    var order_pos_url = "{{ route('dashboard.module.order_form.pos.place_order') }}";
    let a_token = "{{ Session::token() }}";

    cart = undefined;
    products = undefined;
    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cartId) {
            cart = carts[i];
            products = carts[i].products;
        }
    };

    $.ajax({
        method: 'POST',
        url: order_pos_url,
        data: { 
            location: cof_pos_active_location,
            location_type: cof_pos_active_location_type, 
            customer_id: cart.customer_id,
            products: products,
            discounts: cart.discounts,
            subtotal: cart.subtotal,
            discount: cart.discount,
            total: cart.total,
            vat: cart.vat,
            _token: a_token
        }
    })
    .done(function(data) {
        if (data.status == "success"){
            orderSuccesfullyPlacedFromCart(cartId, data.order_number);
        }
        else{
            $('#cof_placeOrderBtnNow').html('Bestellen');
            $('#cof_placeOrderBtnNow').prop('disabled', false);

            $('.error_span:first').html(' Er is iets misgelopen, probeer het later nog eens!');
            $('.error_bag:first').removeClass('hidden');
        }
    });
}

function orderSuccesfullyPlacedFromCart(cartId, order_number) {
    cof_pos_processing_order = false;
    resetPaymentModal();
    printTicketFromCart(cartId, order_number);
    //addCartToOrder(cartId, order_number);
    //removeCart(cartId);
}
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */








/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
function addSelectedCouponToCart(cartId) {
    let coupon = {
        id: $('input[name="coupon_selector"]:checked').val(),
        name: $('input[name="coupon_selector"]:checked').attr('data-name'),
        active: $('input[name="coupon_selector"]:checked').attr('data-active') == 1 ? true : false,
        valid_from: parseInt($('input[name="coupon_selector"]:checked').attr('data-valid-from')),
        valid_until: parseInt($('input[name="coupon_selector"]:checked').attr('data-valid-until')),
        customers: $('input[name="coupon_selector"]:checked').attr('data-customers').length > 0 ? $('input[name="coupon_selector"]:checked').attr('data-customers').split(',') : [],
        minimum: parseInt($('input[name="coupon_selector"]:checked').attr('data-minimum')),
        available: {
            total: parseInt($('input[name="coupon_selector"]:checked').attr('data-available-total')),
            customer: parseInt($('input[name="coupon_selector"]:checked').attr('data-available-customer'))
        },
        conditions: JSON.parse($('input[name="coupon_selector"]:checked').attr('data-conditions')),
        type: $('input[name="coupon_selector"]:checked').attr('data-discount-type'),
        value: $('input[name="coupon_selector"]:checked').attr('data-discount-value'),
        apply_on: $('input[name="coupon_selector"]:checked').attr('data-apply-on'),
        apply_product: $('input[name="coupon_selector"]:checked').attr('data-apply-product').length > 0 ? $('input[name="coupon_selector"]:checked').attr('data-apply-product') : null,
        uncompatible_discounts: $('input[name="coupon_selector"]:checked').attr('data-uncompatible-discounts').length > 0 ? $('input[name="coupon_selector"]:checked').attr('data-uncompatible-discounts').split(',') : [],
        remove_incompatible: $('input[name="coupon_selector"]:checked').attr('data-remove-incompatible') == 1 ? true : false,
        used_by: []
    }

    removeCouponErrorText();

    if (!isCouponValidForCart(coupon, cartId)) {
        updateCouponErrorText(isCouponValidForCart(coupon, cartId, false));
        return false;
    }

    addCouponToCart(coupon, cartId);
    closeCouponModal();
}

function addCouponToCart(coupon, cart_id) {
    let cart = getCartFromStorage(cart_id);

    if (coupon.remove_incompatible) {
        if (coupon.uncompatible_discounts.length > 0) {
            for (var cud = 0; cud < coupon.uncompatible_discounts.length; cud++) {
                removeCouponFromCart(coupon.uncompatible_discounts[cud], cart_id);
            };
        }
        
        for (var cd = 0; cd < cart.discounts.length; cd++) {
            for (var cdud = 0; cdud < cart.discounts[cd].uncompatible_discounts.length; cdud++) {
                if (cart.discounts[cd].uncompatible_discounts[cdud] == coupon.id) {
                    removeCouponFromCart(cart.discounts[cd].id, cart_id);
                } 
            };        
        };
    }

    let carts = getAllCartsFromStorage();

    for (var q = 0; q < carts.length; q++) {
        if(carts[q].id == cart_id) {
            carts[q].discounts.push(coupon);
        }
    };
    localStorage.setItem('cof_carts', JSON.stringify(carts));
    calculateTotalPrice(cart_id);
    displayDiscountsForCart(cart_id);
}

function removeCouponFromCart(coupon, cart_id) {
    let carts = getAllCartsFromStorage();
    let discounts = {};
    for (var q = 0; q < carts.length; q++) {
        if(carts[q].id == cart_id) {
            discounts = carts[q].discounts;

            for (var k = 0; k < discounts.length; k++) {
                if (discounts[k].id == coupon) {
                    discounts.splice(k, 1);
                    break;
                }
            };

            carts[q].discounts = discounts;
            break;
        }
    };

    localStorage.setItem('cof_carts', JSON.stringify(carts));
    calculateTotalPrice(cart_id);
    displayDiscountsForCart(cart_id);
}

function resetCouponsForCart(cart_id) {
    let discounts = getAllDiscountsForCart(cart_id);

    for (var k = 0; k < discounts.length; k++) {
        removeCouponFromCart(discounts[k].id, cart_id);
    };

    for (var k = 0; k < discounts.length; k++) {
        if (isCouponValidForCart(discounts[k], cart_id)) {
            addCouponToCart(discounts[k], cart_id);
        }
    };
}

function displayDiscountsForCart(cart_id) {
    let discounts = getAllDiscountsForCart(cart_id);

    $('.cof_cartCouponItem:not(:first)').remove();
    $('.cof_cartCouponItem:first').addClass('d-none');
    for (var k = 0; k < discounts.length; k++) {
        if(k > 0) {
            $('.cof_cartCouponItem:first').clone().appendTo('#cof_cartCouponWrapper');
        } 

        $('.cof_cartCouponItem:last').attr('data-coupon', discounts[k].id);
        $('.cof_cartCouponItem:last').find('.cof_couponText').text(discounts[k].name);
        $('.cof_cartCouponItem:last').removeClass('d-none');
    };
}

function isCouponValidForCart(coupon, cartId, status = true) {
    let _now = Math.floor(Date.now() / 1000);

    if (!coupon.active) {
        return status ? false : 'Coupon is niet meer actief';
    }

    if (coupon.valid_from > _now) {
        return status ? false : 'Coupon is nog niet geldig';
    }

    if (coupon.valid_until < _now) {
        return status ? false : 'Coupon is niet meer geldig';
    }

    if (coupon.available.total == 0) {
        return status ? false : 'Coupon kan niet meer gebruikt worden';
    }

    if (!isCouponValidForCustomer(coupon, cartId)) {
        return status ? false : 'Coupon is niet geldig voor geselecteerde klant';
    }

    if (!isCouponCompatibleWithCart(coupon, cartId)) {
        return status ? false : 'Coupon kan niet gecombineerd worden met bestaande coupons';
    }

    if (!isCartMinimumReachedForCoupon(coupon, cartId)) {
        return status ? false : 'Winkelwagen heeft niet genoeg winkelwaarde voor coupon';
    }

    if (!isCouponPassingConditions(coupon, cartId)) {
        return status ? false : 'Winkelwagen heeft niet de juiste inhoud voor coupon';
    }

    return status ? true : '';
}


function isCouponValidForCustomer(coupon, cartId) {
    let cart = getCartFromStorage(cartId);

    if (coupon.customers == "") {
        return true;
    }

    if (!Array.isArray(coupon.customers)) {
        return true;
    }

    if (Array.isArray(coupon.customers) && coupon.customers.length == 0) {
        return true;
    }

    for (var cc = 0; cc < coupon.customers.length; cc++) {
        if (cart.customer_id == parseInt(coupon.customers[cc])) {
            return true;
        }
    };

    //@TODO:coupon.available.customer ++ coupon.used_by (if not guest)
    
    return false;
}

function isCouponCompatibleWithCart(coupon, cartId) {
    let cart = getCartFromStorage(cartId);

    if (coupon.uncompatible_discounts == "") {
        return true;
    }

    if (!Array.isArray(coupon.uncompatible_discounts)) {
        return true;
    }

    if (Array.isArray(coupon.uncompatible_discounts) && coupon.uncompatible_discounts.length == 0) {
        return true;
    }

    if (coupon.remove_incompatible) {
        return true;
    }

    for (var cc = 0; cc < coupon.uncompatible_discounts.length; cc++) {
        for (var cd = 0; cd < cart.discounts.length; cd++) {
            if (coupon.uncompatible_discounts[cc] == cart.discounts[cd].id) {
                return false;
            }  
        };
    };

    for (var cd = 0; cd < cart.discounts.length; cd++) {
        for (var cdud = 0; cdud < cart.discounts[cd].uncompatible_discounts.length; cdud++) {
            if (cart.discounts[cd].uncompatible_discounts[cdud] == coupon.id) {
                return false;
            } 
        };        
    };

    return true;
}

function isCartMinimumReachedForCoupon(coupon, cartId) {
    let cart = getCartFromStorage(cartId);

    if(coupon.minimum > cart.subtotal) {
        return false;
    }

    return true;
}

function isCouponPassingConditions(coupon, cartId) {
    let cart = getCartFromStorage(cartId);
    let products = getProductsForCartFromStorage(cartId);

    if (coupon.conditions == "") {
        return true;
    }

    if (!Array.isArray(coupon.conditions)) {
        return true;
    }

    if (Array.isArray(coupon.conditions) && coupon.conditions.length == 0) {
        return true;
    }

    for (var ccs = 0; ccs < coupon.conditions.length; ccs++) {
        if (!isCouponConditionPassedByCart(coupon.conditions[ccs], cart)) {
            return false;
        }
    };

    return true;
}

function isCouponConditionPassedByCart(condition, cart) {
    let productsThatPassed = [];
    let productsNeeded = condition.min_quantity;

    for (var cr = 0; cr < condition.rules.length; cr++) {
        if (isCouponConditionRulePassedByCart(condition.rules[cr], cart)) {
            productsThatPassed = productsThatPassed.concat(getProductsFromCouponConditionRuleByCart(condition.rules[cr], cart));
        }
    };

    if (condition.rules.length > 0 && productsNeeded <= productsThatPassed.length) {
        return true;
    } else if (condition.rules.length == 0) {
        return true;
    } 

    return false;
}

function isCouponConditionRulePassedByCart(rule, cart) {
    if (rule.type == 'product') {
        for (var cprl = 0; cprl < cart.products.length; cprl++) {
            if (rule.value == cart.products[cprl].id) {
                return true;
            }
        };

        return false;
    }

    if (rule.type == 'collection') {
        for (var i = 0; i < cart.products.length; i++) {
            if (rule.value == getCategoryIdForProduct(cart.products[i].id)) {
                return true;
            }
        };

        return false;
    }

    return false;
}

function getProductsFromCouponConditionRuleByCart(rule, cart) {
    let productsIds = [];

    if (rule.type == 'product') {
        for (var i = 0; i < cart.products.length; i++) {
            if (rule.value == cart.products[i].id) {
                for (var q = 0; q < cart.products[i].quantity; q++) {
                    productsIds.push(cart.products[i].id);
                };
            }
        };
    }

    if (rule.type == 'collection') {
        for (var i = 0; i < cart.products.length; i++) {
            if (rule.value == getCategoryIdForProduct(cart.products[i].id)) {
                for (var q = 0; q < cart.products[i].quantity; q++) {
                    productsIds.push(cart.products[i].id);
                };
            }
        };
    }

    return productsIds;
}

function updateCouponErrorText(text) {
    $('#cof_couponErrorText').text(text);
    $('#cof_couponErrorText').removeClass('d-none');
}

function removeCouponErrorText() {
    $('#cof_couponErrorText').text('');
    $('#cof_couponErrorText').addClass('d-none');
}

function closeCouponModal() {
    removeCouponErrorText();
    $('#couponsModal').modal('hide');
}

function getAllDiscountsForCart(cart_id) {
    let carts = getAllCartsFromStorage();
    let discounts = {};
    for (var n = 0; n < carts.length; n++) {
        if(carts[n].id == cart_id) {
            discounts = carts[n].discounts;
        }
    };

    return discounts;
}
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */








/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
function productNeedsModal(product_id) {
    let productNeedsModal = false;

    if(productHasAttributes(product_id)) {
        productNeedsModal = true;
    }

    if(productHasOptions(product_id)) {
        productNeedsModal = true;
    }

    if(productHasExtras(product_id)) {
        productNeedsModal = true;
    }

    return productNeedsModal;
}

function productHasAttributes(product_id) {
    attributes = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-attributes');
    if(attributes == '[]') { 
        return false;
    }

    return true;
}

function productHasOptions(product_id) {
    options = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-options');
    
    if(options == '[]') {
        return false;
    }

    return true;
}

function productHasExtras(product_id) {
    extras = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-extras');
    
    if(extras == '[]') {
        return false;
    }

    return true;
}

function addProductToModal(elem) {
    product_id = elem.attr('data-product-id');
    product_name = elem.attr('data-product-name');
    current_price = elem.attr('data-current-price');
    quantity = 1;
    total_price = parseFloat(current_price) * parseInt(quantity);

    product_attributes = JSON.parse(elem.attr('data-product-attributes'));
    product_options = JSON.parse(elem.attr('data-product-options'));
    if ( elem.attr('data-product-extras') !== undefined ) {
        product_extras = JSON.parse(elem.attr('data-product-extras'));
    } else {
        product_extras = [];
    }

    resetOptionsModal();
    setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_attributes, product_options, product_extras)
    $('#optionsModal').modal();
}

function getProductDetails(product_id) {
    let product = {
        id: product_id,
        name: $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-name'),
        attribute: '',
        options: [],
        extras: [],
        quantity: 1,
        vat: {
            delivery: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-delivery')),
            takeout: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-takeout')),
            on_the_spot: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromProductId(product_id)),
        //vat_total_price: Number(getVatPriceFromProductId(product_id)),
        current_price: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-current-price')),
        total_price: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-current-price'))
        
    }

    return product;
}

function getProductDetailsFromModal(product_id) {
    let product = {
        id: product_id,
        name: $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-name'),
        attribute: getSelectedProductAttribute(product_id),
        options: getSelectedProductOptions(),
        extras: getSelectedProductExtras(),
        quantity: getSelectedProductQuantity(product_id),
        vat: {
            delivery: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-delivery')),
            takeout: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-takeout')),
            on_the_spot: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromSelectedProduct(product_id)),
        //vat_total_price:(getSelectedProductQuantity(product_id) * Number(getVatPriceFromSelectedProduct(product_id))),
        current_price: getSelectedProductUnitPrice(product_id),
        total_price: (getSelectedProductQuantity(product_id) * getSelectedProductUnitPrice(product_id))
    }

    return product;
}

function getProductDetailsFromCartItemElement(cart_id, elem, copy_quantity = true) {
    product_id = elem.parents('.cof_cartProductListItem').first().attr('data-product-id');

    if(copy_quantity === -1) {
        quantity = -1;
    }

    if(copy_quantity === true) {
        quantity = parseInt(elem.parents('.cof_cartProductListItem').first().attr('data-quantity'))
    } else if(copy_quantity === false) {
        quantity = 1;
    }

    let product = {
        id: product_id,
        name: $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-name'),
        attribute: elem.parents('.cof_cartProductListItem').first().attr('data-attribute-name').length > 0 ? elem.parents('.cof_cartProductListItem').first().attr('data-attribute-name') : '',
        options: elem.parents('.cof_cartProductListItem').first().attr('data-product-options').length > 0 ? JSON.parse(elem.parents('.cof_cartProductListItem').first().attr('data-product-options')) : JSON.parse('[]'),
        extras: elem.parents('.cof_cartProductListItem').first().attr('data-product-extras').length > 0 ? JSON.parse(elem.parents('.cof_cartProductListItem').first().attr('data-product-extras')) : JSON.parse('[]'),
        quantity: quantity,
        vat: {
            delivery: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-delivery')),
            takeout: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-takeout')),
            on_the_spot: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromProductAndUnitPrice(product_id, Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')))),
        //vat_total_price: (quantity) * Number(getVatPriceFromProductAndUnitPrice(product_id, Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')))),
        current_price: Number(Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')).toFixed(2)),
        total_price: Number((Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')) * (quantity)).toFixed(2))
    }

    return product;
}

function getCategoryIdForProduct(product_id) {
    return $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-category-id');
}

function calculateProductAvailability() {
    locationKey = cof_pos_active_location;
    $('.cof_pos_product_card').each(function() {
        productId = $(this).attr('data-product-id');
        q = $(this).attr('data-q').split(',')
        for (var i = 0; i < q.length; i++) {
            if(q[i].search(''+locationKey+'=') !== -1) {
                max_q = q[i].split('=').pop();
                $(this).attr('data-max-q', max_q);
                if(parseInt(max_q) == 0) {
                    $(this).addClass('unavailable');
                    $('.cof_btnAddProductToCart[data-product-id='+productId+']').prop('disabled', true);
                    $('.cof_btnAddProductOptionsToCart[data-product-id='+productId+']').prop('disabled', true);
                    $('.cof_btnAddProductAttributeToCart[data-product-id='+productId+']').prop('disabled', true);
                    $('.cof_btnAddProductAttributeOptionsToCart[data-product-id='+productId+']').prop('disabled', true);
                } else {
                    $(this).removeClass('unavailable');
                    $('.cof_btnAddProductToCart[data-product-id='+productId+']').prop('disabled', false);
                    $('.cof_btnAddProductOptionsToCart[data-product-id='+productId+']').prop('disabled', false);
                    $('.cof_btnAddProductAttributeToCart[data-product-id='+productId+']').prop('disabled', false);
                    $('.cof_btnAddProductAttributeOptionsToCart[data-product-id='+productId+']').prop('disabled', false);
                }
            }
        };
    });
    //@TODO: add a check for products already in cart
}

function getSelectedProductAttribute(product_id)
{
    if ($('.attributes_modal_item_button_group[data-product-id='+product_id+']').length) {
        attribute_name = $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-name') == undefined ? $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input").first().attr('data-attribute-name') : $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-name');
    } else {
        attribute_name = '';
    }

    return attribute_name;
}

function getSelectedProductAttributePrice(product_id)
{
    attribute_price = 0.00;
    
    if ($('.attributes_modal_item_button_group[data-product-id='+product_id+']').length) {
        attribute_price = $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-name') == undefined ? $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input").first().attr('data-attribute-price') : $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-price');
        if(attribute_price == undefined) {
            attribute_price = 0.00;
        }
    } 

    return attribute_price;
}

function getSelectedProductOptions()
{
    product_options = [];
    if(!$('#optionsModalBody').hasClass('d-none')) {
        $('.options_modal_row').each(function() {
            
            option_name = $(this).attr('data-product-option-name');
            
            type = $(this).attr('data-product-option-type');
            input_name = $(this).attr('data-product-option-input-name');
            if(type == 'radio') {
                option_value = $(this).find('input[name='+input_name+']:checked').first().val();
            } else if(type == 'select') {
                option_value = $(this).find('select[name='+input_name+']').first().val();
            }

            option_object = {name: option_name, value: option_value};
            product_options.push(option_object)
        });
    }

    return product_options;
}

function getSelectedProductExtras()
{
    product_options = [];
    if(!$('#extrasModalBody').hasClass('d-none')) {
        $('.extras_modal_row').each(function() {
            
            option_name = $(this).find('input:checked').first().val();
            option_value = $(this).find('input:checked').first().attr('data-product-extra-item-price');

            option_object = {name: option_name, value: option_value};
            product_options.push(option_object)
        });
    }

    return product_options;
}

function getSelectedProductExtrasPrice(product_id)
{
    extras_price = 0;
    if(!$('#extrasModalBody').hasClass('d-none')) {
        $('.extras_modal_row').each(function() {
            extras_price = Number(extras_price) + ($(this).find('input:checked').first().attr('data-product-extra-item-price') !== undefined ? Number($(this).find('input:checked').first().attr('data-product-extra-item-price')) : 0);
        });
    }

    return extras_price;
}

function getSelectedProductQuantity(product_id)
{
    return parseInt($('#addProductFromModalToCartButton[data-product-id='+product_id+']').attr('data-quantity'));
}

function getSelectedProductUnitPrice(product_id)
{
    base_price = $('#addProductFromModalToCartButton[data-product-id='+product_id+']').attr('data-current-price');
    attribute_price = getSelectedProductAttributePrice(product_id);
    extras_price = getSelectedProductExtrasPrice(product_id);
 
    if(attribute_price == 0) {
        return Number(base_price) + Number(extras_price);
    }

    return Number(attribute_price) + Number(extras_price);
}
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */








/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
function calculateTotalPrice(cart_id) {

    // let total_price = getTotalPrice(cart_id) + 0;
    // $('.cof_cartTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

    // let total_vat = getTotalVat(cart_id);
    // $('.cof_cartTotalVatPrice').text('€ '+parseFloat(total_vat).toFixed(2).replace('.', ','));

    // let subtotal = Number((total_price - total_vat).toFixed(2));
    // $('.cof_cartSubtotalPrice').text('€ '+parseFloat(subtotal).toFixed(2).replace('.', ','));

    let subtotal = getSubtotalPrice(cart_id) + 0;
    $('.cof_cartSubtotalPrice').text('€ '+parseFloat(subtotal).toFixed(2).replace('.', ','));

    let discount_price = getTotalDiscount(cart_id);
    $('.cof_cartDiscountPrice').text('€ '+parseFloat( (discount_price <= subtotal ? discount_price : subtotal) ).toFixed(2).replace('.', ','));
    
    let total_price = Number((subtotal - (discount_price <= subtotal ? discount_price : subtotal)).toFixed(2));
    $('.cof_cartTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

    let total_vat = getTotalVat(cart_id);
    $('.cof_cartTotalVatPrice').text('€ '+parseFloat(total_vat).toFixed(2).replace('.', ','));

    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cart_id) {
            carts[i].subtotal = subtotal;
            carts[i].discount = discount_price;
            carts[i].total = total_price;
            carts[i].vat = total_vat;
        }
    };
    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

function getSubtotalPrice(cart_id) {
    products = getProductsForCartFromStorage(cart_id);
    totalPrice = 0;

    for (var i = 0; i < products.length; i++) {
        totalPrice = totalPrice + products[i].total_price;
    };

    return Number((totalPrice).toFixed(2));
}

function getTotalPrice(cart_id) {
    let subtotal = getSubtotalPrice(cart_id);
    let discount_price = getTotalDiscount(cart_id);

    return Number((subtotal - (discount_price <= subtotal ? discount_price : subtotal)).toFixed(2));
}

function getTotalVat(cart_id) {
    total_price = 0;
    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cart_id) {
            products = carts[i].products;
        }
    };

    return getCartTotalVatFromProducts(products);
}

function calculateShippingPrice() {
    if($('.cof_location_radio:checked').attr('data-location-type') == 'delivery') {
        $('#cof_CartProductListShippingLine').removeClass('d-none');
        $('#cof_CartProductListShippingLine').removeClass('hidden');
    } else {
        $('#cof_CartProductListShippingLine').addClass('d-none');
        $('#cof_CartProductListShippingLine').addClass('hidden');
    }

    location_shipping_price = $('.cof_location_radio:checked').attr('data-delivery-cost');
    $('.cof_cartShippingPrice').text('€ '+parseFloat(location_shipping_price).toFixed(2).replace('.', ','));

    return parseFloat(location_shipping_price);
}

function getCartTotalVatFromProducts(products) {
    totalPricesPerVatRate = {};
    totalVat = 0;

    for (var i = 0; i < products.length; i++) {

        vat_rate = getVatPercentageForProduct(products[i].id);
        if(vat_rate in totalPricesPerVatRate) {
            totalPricesPerVatRate[vat_rate] = (totalPricesPerVatRate[vat_rate] + products[i].total_price); 
        } else {
            totalPricesPerVatRate[vat_rate] = products[i].total_price;
        }
           
    };

    Object.keys(totalPricesPerVatRate).forEach(function(rate) {
        totalVat = totalVat + Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2));
    });

    return Number((totalVat).toFixed(2));
}

// function getVatPriceFromProductId(product_id) {
//     vat_percentage = getVatPercentageForProduct(product_id);

//     product_price = Number($(selector).attr('data-current-price'));
//     divider = parseInt(100 + vat_percentage);
//     vat_price = ((product_price / divider) * vat_percentage);
//     return Number(vat_price);
// }

// function getVatPriceFromSelectedProduct(product_id) {
//     vat_percentage = getVatPercentageForProduct(product_id);

//     product_price = Number(getSelectedProductUnitPrice(product_id));
//     divider = parseInt(100 + vat_percentage);

//     vat_price = ((product_price / divider) * vat_percentage);
//     return Number(vat_price);
// }

// function getVatPriceFromProductAndUnitPrice(product_id, unit_price) {
//     vat_percentage = getVatPercentageForProduct(product_id);

//     product_price = Number(unit_price);
//     divider = parseInt(100 + vat_percentage);

//     vat_price = ((product_price / divider) * vat_percentage);
//     return Number(vat_price);
// }

function getVatFromPriceAndRate(price, rate) {
    vat_percentage = parseInt(rate);

    product_price = Number(price);
    divider = parseInt(100 + vat_percentage);

    vat_price = ((product_price / divider) * vat_percentage);
    return Number(vat_price);
}

function getVatPercentageForProduct(product_id) {
    selector = '.cof_pos_product_card[data-product-id='+product_id+']';
    location_type = getActiveLocationType();

    if(location_type == 'delivery') {
        vat_percentage = Number($(selector).attr('data-vat-delivery'));
    }

    if(location_type == 'takeout') {
        vat_percentage = Number($(selector).attr('data-vat-takeout'));
    }

    if(location_type == 'on-the-spot') {
        vat_percentage = Number($(selector).attr('data-vat-on-the-spot'));
    }

    return vat_percentage;
}

function getTotalDiscount(cart_id) {
    let cart = getCartFromStorage(cart_id);
    let discounts = getAllDiscountsForCart(cart_id);
    let products = getProductsForCartFromStorage(cart_id);

    totalDiscount = 0;

    for (var i = 0; i < products.length; i++) {

        let productDiscount = 0;
        let productPrice = products[i].total_price;
        
        for (var k = 0; k < discounts.length; k++) {
            if (isDiscountApplicableForProduct(discounts[k], products[i], cart_id)) {
                productDiscount = productDiscount + calculateDiscount(discounts[k], productPrice);
                productPrice = calculateDiscount(discounts[k], productPrice, true);
            }
        };

        totalDiscount = totalDiscount + productDiscount;
    };

    for (var kd = 0; kd < discounts.length; kd++) {
        if (discounts[kd].apply_on == "cart" && discounts[kd].type == "currency") {
            totalDiscount = totalDiscount + Number(parseFloat(discounts[kd].value).toFixed(2));
        }
    };

    return Number((totalDiscount).toFixed(2));
}

function isDiscountApplicableForProduct(coupon, product, cart_id) {
    if (coupon.apply_on == "cart" && coupon.type == "percentage") {
        return true;
    }

    if (coupon.apply_on == "conditions") {
        let productsThatPassed = [];
        let cart = getCartFromStorage(cart_id);

        for (var cck = 0; cck < coupon.conditions.length; cck++) {
            for (var cr = 0; cr < coupon.conditions[cck].rules.length; cr++) {
                if (isCouponConditionRulePassedByCart(coupon.conditions[cck].rules[cr], cart)) {
                    productsThatPassed = productsThatPassed.concat(getProductsFromCouponConditionRuleByCart(coupon.conditions[cck].rules[cr], cart));
                }
            };
        };

        if (productsThatPassed.includes(product.id)) {
            return true;
        }
            
    }

    if (coupon.apply_on == "product") {
        if (coupon.apply_product == product.id) {
            return true;
        }
    }

    return false;
}

function calculateDiscount(coupon, price, applied = false) {
    let discountValue = 0;
    let discountPrice = price;

    discountValue = calculateDiscountValue(coupon, price);
    discountPrice = applyDiscount(coupon, discountPrice);

    return !applied ? discountValue : discountPrice;
}

function calculateDiscountValue(coupon, price) {
    switch (coupon.type) {
        case 'currency':
            return Number((Number(coupon.value) > price) ? price : Number(coupon.value));
            break;
        case 'percentage':
            return Number((price * (Number(coupon.value) / 100)));
            break;
    }
}

function applyDiscount(coupon, price) {
    return price - calculateDiscountValue(coupon, price);
}

function formatPrice(raw) {
    return '€ '+raw.toFixed(2).replace('.', ',')
}
/* END OF PRICE FUNCTIONS */








function openPaymentModal(cartId) {
    $('.cof_checkoutCashInput').val('0.00');
    $('.cof_checkoutCardInput').val('0.00');

    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if( carts[i].id == cartId) {
            /*let products = carts[i].products;
            
            let shipping = 0;*/
            // console.log(carts[i]);
            
            let price = getTotalPrice(cartId);
            $('.cof_checkoutCashFitPayment').text(formatPrice(price));
            $('.cof_checkoutCardFitPayment').text(formatPrice(price));
            $('.cof_checkoutPendingAmount').text(formatPrice(price));
            $('#cof_finalizeOrderBtn').prop('disabled', true);
            $('#paymentModal').modal('show')
        }
    };
    
}

function resetPaymentModal(cartId) {
    $('.cof_checkoutCashInput').val('0.00');
    $('.cof_checkoutCardInput').val('0.00');
    
    let price = 0.00;
    $('.cof_checkoutCashFitPayment').text(formatPrice(price));
    $('.cof_checkoutCardFitPayment').text(formatPrice(price));
    $('.cof_checkoutPendingAmount').text(formatPrice(price));

    $('#cof_finalizeOrderBtn').prop('disabled', true);
    $('#cof_finalizeOrderBtn').text('Bestelling voltooien');

    $('#paymentModal').modal('hide');
}


function placeOrder(products, price, shipping) {
    var order_url = "{{ route('cof.place_order') }}";
    let a_token = "{{ Session::token() }}"
    $.ajax({
        method: 'POST',
        url: order_url,
        data: { 
            location: $('#cof_pos_location').attr('data-active-location'), 
            order_date: '', 
            order_time: '', 
            surname: '', 
            name: '',
            email: '',
            tel: '',
            street: '',
            housenumber: '',
            postalcode: '',
            city: $('#cof_pos_location').attr('data-active-location'),
            remarks: '',
            order: products,
            total: price,
            shipping: shipping,
            legal_approval: '',
            promo_approval: '',
            _token: a_token
        }
    })
    .done(function(data) {
        if (data.status == "success"){
            window.location.href = data.url;
        }
        else{
            $('#cof_placeOrderBtnNow').html('Bestellen');
            $('#cof_placeOrderBtnNow').prop('disabled', false);

            $('.error_span:first').html(' Er is iets misgelopen, probeer het later nog eens!');
            $('.error_bag:first').removeClass('hidden');
        }
    });
}

function validateForm() {
    var valid = true;
    $('.legal_label').first().css('color', '#555');

    $('input[required]').each(function () {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
            valid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    if($('input[name=legal_approval]:checked').length == 0) {
        $('.legal_label').first().css('color', 'red');
    }
    return valid
}

function getProducts() {
    var products = [];
    $('.cof_cartProductListItem').each(function() {
        var elem = $(this);

        products.push({ 
            product_id: elem.attr('data-product-id'),
            attributes: elem.attr('data-attribute-name') == '' ? false : elem.attr('data-attribute-name'), 
            options: elem.attr('data-product-options') == '' ? false : elem.attr('data-product-options'),
            extras: elem.attr('data-product-extras') == '' ? false : elem.attr('data-product-extras'), 
            name: elem.attr('data-product-name'), 
            price: Number(elem.attr('data-unit-price')),
            qty: Number(elem.attr('data-quantity')),
            totprice: Number(elem.attr('data-total-price'))
        })

    });
    return products;
}



function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        .replace('-','')
        ;
}

function resetFirstProduct()
{
    $('.cof_cartProductListItem:first').attr('data-product-id', 0);
    $('.cof_cartProductListItem:first').attr('data-quantity', 0);
    $('.cof_cartProductListItem:first').attr('data-product-name', '');
    $('.cof_cartProductListItem:first').attr('data-attribute-name', '');
    $('.cof_cartProductListItem:first').attr('data-product-options', '');
    $('.cof_cartProductListItem:first').attr('data-unit-price', 0);
    $('.cof_cartProductListItem:first').attr('data-total-price', 0);
    $('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:not(:first)').remove();
    $('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');
}

function updateProductListItemAttributes(cart_id, selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, quantity, current_price, total_price)
{
    productListItem = $('.cof_cartTab[data-cart-id='+cart_id+']').find(''+selector+'');

    productListItem.attr('data-product-id', product_id);
    productListItem.attr('data-product-name', product_name);
    productListItem.attr('data-attribute-name', attribute_name);
    
    if(product_options_json.length > 0 && product_options_json !== undefined && product_options_json !== '[]') {
        prod_json = ""+product_options_json+"";
    } else {
        prod_json = '';
    }

    if(product_extras_json.length > 0 && product_extras_json !== undefined && product_extras_json !== '[]') {
        prodex_json = ""+product_extras_json+"";
    } else {
        prodex_json = '';
    }

    productListItem.attr('data-product-options', prod_json);
    productListItem.attr('data-product-extras', prodex_json);
    productListItem.attr('data-quantity', quantity);
    productListItem.attr('data-unit-price', current_price);
    productListItem.attr('data-total-price', total_price);
    productListItem.attr('data-unique-el', '');

    if(quantity > 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').removeClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-trash')
                .addClass('fa-minus');
    }

    if(quantity == 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').addClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-minus')
                .addClass('fa-trash');
    }

    full_name = product_name
                 + ((attribute_name == '' || attribute_name == undefined) ? '' : ' - ' + attribute_name);

    productListItem.find('.cof_cartProductListItemOptions:not(:first)').remove();
    productListItem.find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');

    if(product_options_json !== '' && product_options_json !== undefined && product_options_json !== '[]') {
        product_options = JSON.parse(product_options_json);

        productListItem.find('.cof_cartProductListItemOptions:last').addClass('d-block').removeClass('d-none');

        for (var i = 0; i < product_options.length; i++) {
            if(i > 0) {
                productListItem.find('.cof_cartProductListItemOptions:first').clone().appendTo(productListItem.find('.cof_cartProductListDetails:first'));
            }

            productListItem.find('.cof_cartProductListItemOptions:last')
                .find('.cof_cartProductListItemOptionName').text(product_options[i]['name']);
            productListItem.find('.cof_cartProductListItemOptions:last')
                .find('.cof_cartProductListItemOptionValue').text(product_options[i]['value']);

        };
    } 



    productListItem.find('.cof_cartProductListItemExtras:not(:first)').remove();
    productListItem.find('.cof_cartProductListItemExtras:first').appendTo(productListItem.find('.cof_cartProductListDetails:first'));
    productListItem.find('.cof_cartProductListItemExtras:first').removeClass('d-block').addClass('d-none');

    if(product_extras_json !== '' && product_extras_json !== undefined && product_extras_json !== '[]') {
        product_extras = JSON.parse(product_extras_json);

        productListItem.find('.cof_cartProductListItemExtras:last')
                        .addClass('d-block')
                        .removeClass('d-none');

        extra_faulty_check = false;
        g = 0;
        for (var i = 0; i < product_extras.length; i++) {
            //console.log('check ot the ',i,' th : ', product_extras[i]);
            if(!$.isEmptyObject(product_extras[i])) {
                extra_faulty_check = true;
                if(g > 0) {
                    productListItem.find('.cof_cartProductListItemExtras:first').clone().appendTo(productListItem.find('.cof_cartProductListDetails:first'));
                }

                productListItem.find('.cof_cartProductListItemExtras:last').find('.cof_cartProductListItemOptionName').text(product_extras[i]['name']);
                productListItem.find('.cof_cartProductListItemExtras:last').find('.cof_cartProductListItemOptionValue').text( formatPrice(parseFloat(product_extras[i]['value'])) );

                g++;
            }

        };

        if(!extra_faulty_check) {
            productListItem.find('.cof_cartProductListItemExtras:last').addClass('d-none').removeClass('d-block');
        }
    } 

    productListItem.find('.cof_cartProductListItemFullName').text(full_name);
    productListItem.find('.cof_cartProductListItemQuantity:not(input)').text(quantity);
    productListItem.find('.cof_cartProductListItemQuantity:input').val(quantity);
    productListItem.find('.cof_cartProductListItemUnitPrice').text(formatPrice(parseFloat(current_price)));
    productListItem.find('.cof_cartProductListItemTotalPrice').text(formatPrice(parseFloat(total_price)));
}

function updateProductListItemQuantity(cart_id, selector, quantity, total_price)
{   
    productListItem = $('.cof_cartTab[data-cart-id='+cart_id+']').find(''+selector+'');

    newquantity = parseInt(productListItem.attr('data-quantity')) + parseInt(quantity);

    if(quantity === -1 && newquantity === 0) {
        removeProductFromCart(cart_id, selector);
        return 'removed';
    }
    
    productListItem.attr('data-quantity', newquantity);
    productListItem.find('.cof_cartProductListItemQuantity:not(input)').text(newquantity);
    productListItem.find('.cof_cartProductListItemQuantity:input').val(newquantity);

    if(newquantity > 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').removeClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-trash')
                .addClass('fa-minus');
    }

    if(quantity === -1 && newquantity === 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').addClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-minus')
                .addClass('fa-trash');
    }
    
    new_total_price = Number((Number(productListItem.attr('data-total-price')) + Number(total_price)).toFixed(2));
    productListItem.attr('data-total-price', new_total_price);
    productListItem.find('.cof_cartProductListItemTotalPrice').text(formatPrice(parseFloat(new_total_price)));

    productListItem.attr('data-unique-el', '');

    return 'updated';
}

function resetOptionsModal()
{
    $('#attributesModalBody').addClass('d-none');
    $('.attributes_modal_item_button_group').attr('data-product-id', '');
    $('.attributes_modal_item_button:not(:first)').remove();
    $('.attributes_modal_item_button:first').removeClass('active');
    $('.attributes_modal_item_button:first').find('input').attr('id', '').prop('checked', false);
    $('.attributes_modal_item_button:first').find('input').attr('data-attribute-name', '');

    $('.options_modal_row:not(:first)').remove();
    $('.options_modal_row:first').find('.cof_options_radio_item_input:not(:first)').remove();
    $('.options_modal_row:first').find('.cof_options_select_item_input').find('option:not(:first)').remove();
    $('.options_modal_row:first').attr('data-product-option-type', '');
    $('.options_modal_row:first').attr('data-product-option-name', '');
    $('.options_modal_row:first').find('.cof_options_radio_item_input:first').find('input').attr('name', 'cof_options_radio');

    $('.extras_modal_row:not(:first)').remove();
    $('.extras_modal_row:first').find('.extras_item_name').text('cof_extra_name');
    $('.extras_modal_row:first').find('.extras_item_name').attr('for', 'cof_extra_name');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('id', 'cof_extra_name');
    $('.extras_modal_row:first').find('.extras_item_checkbox').val('');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-price', '');
}

function setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_attributes, product_options, product_extras)
{
    //console.log('test es ::',product_name);
    
    $('.options_product_name').text(product_name);
    $('#addProductFromModalToCartButton').attr('data-product-id', product_id);
    $('#addProductFromModalToCartButton').attr('data-current-price', current_price);
    $('#addProductFromModalToCartButton').attr('data-quantity', quantity);
    $('#addProductFromModalToCartButton').attr('data-total-price', total_price);

    if(product_attributes.length > 0) {
        $('#attributesModalBody').removeClass('d-none');
        $('.attributes_modal_item_button_group').attr('data-product-id', product_id);

        for (var i = 0; i < product_attributes.length; i++) {
            if(i > 0) {
                $('.attributes_modal_item_button:first').clone().appendTo('.attributes_modal_item_button_group');
            }

            attribute_name = product_attributes[i]['name'];
            attribute_price = product_attributes[i]['price'];
            
            $('.attributes_modal_item_button:last').removeClass('active');
            $('.attributes_modal_item_button:last').find('input').attr('checked', false);

            // $('.attributes_modal_item_button:last').attr('data-product-attribute-name', attribute_name);
            // $('.attributes_modal_item_button:last').attr('data-product-attribute-price', attribute_price);
            $('.attributes_modal_item_button:last').find('input').attr('id', 'attributeRadio'+i);
            $('.attributes_modal_item_button:last').find('input').attr('data-attribute-name', attribute_name);
            $('.attributes_modal_item_button:last').find('input').attr('data-attribute-price', attribute_price);
            $('.attributes_modal_item_button:last').find('.attributes_modal_item_button_text').attr('id', 'attribute'+i).text(attribute_name+(attribute_price !== null ? ' ('+formatPrice(parseFloat(attribute_price))+')' : ''));

            if(i == 0) {
                $('.attributes_modal_item_button:last').addClass('active');
                $('.attributes_modal_item_button:last').find('input').attr('checked', true);
            }
        }
    }


    radio_ids = [];
    if(product_options.length > 0) {
        $('#optionsModalBody').removeClass('d-none');
        for (var i = 0; i < product_options.length; i++) {
            if(i > 0) {
                $('.options_modal_row:first').clone().appendTo('#optionsModalBody');
            }

            option_name = product_options[i]['name'];
            option_type = product_options[i]['type'];
            option_values = product_options[i]['values'].split(',');
            
            $('.options_modal_row:last').attr('data-product-option-type', option_type);
            $('.options_modal_row:last').attr('data-product-option-name', option_name);
            $('.options_modal_row:last').find('.options_item_name').text(option_name);
            
            if(option_type == 'radio') {
                $('.options_modal_row:last').find('.options_modal_item_radio').removeClass('d-none hidden');
                $('.options_modal_row:last').find('.options_modal_item_select').addClass('d-none hidden');

                $('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();

                $('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_radio_'+i);

                for (var k = 0; k < option_values.length; k++) {
                    if(k > 0) {
                        $('.options_modal_row:last').find('.cof_options_radio_item_input:first').clone().appendTo('.options_modal_row:last .cof_options_radio_item_input_group');
                    }
                    $('.options_modal_row:last')
                        .find('.cof_options_radio_item_input:last input:first').first()
                        .val(option_values[k])
                        .attr('id', 'cofOptionsRadioId'+i+'_'+k)
                        .attr('name', 'cof_options_radio_'+i)
                        .attr('checked', false);
                    $('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('span').text(option_values[k]).attr('for', 'cofOptionsRadioId'+i+'_'+k);
                    $('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('label').attr('for', 'cofOptionsRadioId'+i+'_'+k);

                    if(k == 0) {
                        radio_ids.push('#cofOptionsRadioId'+i+'_'+k);
                    }

                }

            } 
            if(option_type == 'select') {
                $('.options_modal_row:last').find('.options_modal_item_radio').addClass('d-none hidden');
                $('.options_modal_row:last').find('.options_modal_item_select').removeClass('d-none hidden');

                $('.options_modal_row:last').find('.cof_options_select_item_input').attr('name', 'cof_options_select'+i);
                $('.options_modal_row:last').find('.cof_options_select_item_input').attr('id', 'cof_options_selectId'+i);

                $('#cof_options_selectId'+i).find('.cof_options_option_input:not(:first)').remove();
                $('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();
                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').attr('name', 'cof_options_radio').attr('id', '');

                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('label:first').attr('for', '');
                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('input:first').attr('name', 'cof_options_radio').attr('id', '').val('');

                $('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_select'+i);

                for (var j = 0; j < option_values.length; j++) {
                    if(j > 0) {
                        $('#cof_options_selectId'+i+' .cof_options_option_input:first').first().clone().appendTo('#cof_options_selectId'+i);
                    }

                    $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().val(option_values[j]);
                    $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().text(option_values[j]);
                    $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().prop('selected', false);

                }
                $('#cof_options_selectId'+i).val($('#cof_options_selectId'+i).find('option').first().val());
            }
            
        };

        for (var i = 0; i < radio_ids.length; i++) {
            $(''+radio_ids[i]+'').prop('checked', true);
        };
        
    } else {
        $('#optionsModalBody').addClass('d-none');
    }


    if(product_extras.length > 0) {
        $('#extrasModalBody').removeClass('d-none');
        for (var i = 0; i < product_extras.length; i++) {
            if(i > 0) {
                $('.extras_modal_row:first').clone().appendTo('#extrasModalBody');
            }

            extra_name = product_extras[i]['name'];
            extra_slug = extra_name.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
            extra_price = product_extras[i]['price'];
            
            //$('.extras_modal_row:last').attr('data-product-option-type', option_type);
            //$('.extras_modal_row:last').attr('data-product-option-name', option_name);
            $('.extras_modal_row:last').find('.extras_item_name')
                            .text( extra_name + ' (€ ' + parseFloat(extra_price).toFixed(2).replace('.', ',') + ')' );
            $('.extras_modal_row:last').find('.extras_item_name').attr('for', extra_slug);
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('id', extra_slug);
            $('.extras_modal_row:last').find('.extras_item_checkbox').val(extra_name);
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-price', parseFloat(extra_price));
            $('.extras_modal_row:last').find('.extras_item_checkbox').prop('checked', false);
            
            
            
        };
        
    } else {
        $('#extrasModalBody').addClass('d-none');
    }
    
}

function printTicketFromCart(cart_id, order_number) {
    let cart = getCartFromStorage(cart_id);
    let items = getFormattedItemsForTicket(cart.products, cart.discounts);

    let job = {
        cart: cart,
        items: items,
        subtotal: cart.subtotal,
        discount: cart.discount,
        total: cart.total
    };

    printJob(job);
}

function getFormattedItemsForTicket(products, discounts) {
    let items = [];

    for (var p = 0; p < products.length; p++) {
        console.log('check out the lines per product :: ', formatLinesForProduct(products[p]));
        items = items.concat(formatLinesForProduct(products[p]));
    };

    console.log('check out aaaaall of the lines :: ', items);

    return items;
}

function formatLinesForProduct(product) {
    let lines = [];
    let line = "";

    line += product.quantity;
    line += " ";
    line += product.name;
    if (product.attribute !== "" && product.attribute.length > 0) {
        line += ": ";
        line += product.attribute;
    }

    if (line.length < 41) {
        for (var ll = 0; ll < (40 - line.length); ll++) {
            line += " ";
        };
    }

    if (line.length > 40) {
        line = truncateString(line, 37);
    }

    if (product.total_price > 9.99 && product.total_price < 100 ) {
        line += " ";
    }

    if (product.total_price > 0.99 && product.total_price < 10) {
        line += "  ";
    }

    line += (product.total_price).toFixed(2);

    if (getVatPercentageForProduct(product.id) == 21) {
        line += " A";
    }

    if (getVatPercentageForProduct(product.id) == 12) {
        line += " B";
    }

    if (getVatPercentageForProduct(product.id) == 6) {
        line += " C";
    }

    if (getVatPercentageForProduct(product.id) == 0) {
        line += " D";
    }

    lines.push(line);

    if (product.extras.length > 0) {
        for (var pex = 0; pex < product.extras.length; pex++) {
            if (!$.isEmptyObject(product.extras[pex])) {
                let extraLine = "";
                extraLine += "  1 ";
                extraLine += product.extras[pex].name;

                if (extraLine.length > 40) {
                    extraLine = truncateString(extraLine, 37);
                }

                if (extraLine.length < 41) {
                    for (var ell = 0; ell < (40 - extraLine.length); ell++) {
                        extraLine += " ";
                    };
                }

                let extrasPrice = Number(product.extras[pex].value);

                if (extrasPrice > 9.99 && extrasPrice < 100 ) {
                    extraLine += " ";
                }

                if (extrasPrice > 0.99 && extrasPrice < 10) {
                    extraLine += "  ";
                }

                extraLine += (extrasPrice).toFixed(2);

                // if (getVatPercentageForProduct(product.id) == 21) {
                //     extraLine += " A";
                // }

                // if (getVatPercentageForProduct(product.id) == 12) {
                //     extraLine += " B";
                // }

                // if (getVatPercentageForProduct(product.id) == 6) {
                //     extraLine += " C";
                // }

                // if (getVatPercentageForProduct(product.id) == 0) {
                //     extraLine += " D";
                // }
                extraLine += "  ";

                lines.push(extraLine);
            }
        };
    }
console.log('all the lines :: ', lines);
    return lines;
}

function truncateString(str, num) {
  if (str.length <= num) {
    return str;
  }
  return str.slice(0, num) + '...';
}

function getLineSize() {
    return 48;
}

function printJob(job) {
    if (jspmWSStatus()) {

        // Gen sample label featuring logo/image, barcode, QRCode, text, etc by using JSESCPOSBuilder.js

        var escpos = Neodynamic.JSESCPOSBuilder;
        var doc = new escpos.Document();
        escpos.ESCPOSImage.load("{{ChuckSite::module('chuckcms-module-order-form')->getSetting('pos.ticket_logo')}}")
            .then(logo => {

                // logo image loaded, create ESC/POS commands
                doc.setCharacterCodeTable(19)
                    .align(escpos.TextAlignment.Center)
                    .image(logo, escpos.BitmapDensity.D24)
                    .feed(2)
                    .font(escpos.FontFamily.A)
                    .align(escpos.TextAlignment.Center)
                    .style([escpos.FontStyle.Bold])
                    .size(0, 0)
                    .text("DONUTTELLO")
                    .font(escpos.FontFamily.B)
                    .size(0, 0)
                    .text("Bergstraat 27,")
                    .text("2220 Heist-op-den-Berg")
                    .text("BE0721.497.975")
                    .feed(2)
                    .font(escpos.FontFamily.A)
                    .size(0, 0)
                    .text("KASTICKET")
                    .align(escpos.TextAlignment.LeftJustification)
                    .feed(2)
                    .drawLine();

                for (var jit = 0, len = job.items.length; jit < len; jit++) {
                    doc.align(escpos.TextAlignment.LeftJustification).text(job.items[jit]);
                }

                doc.drawLine()
                    .font(escpos.FontFamily.B)
                    .style([escpos.FontStyle.Bold])
                    .size(1, 1)
                    .drawTable(["Totaal", "€ "+(job.total.toFixed(2))])
                    .feed(2)
                    .font(escpos.FontFamily.A)
                    .size(0, 0)
                    .align(escpos.TextAlignment.Center)
                    .text("Bedankt voor uw bezoek aan Donuttello!")
                    .text("Geef uw mening over uw bezoek:")
                    .qrCode('https://donuttello.com', new escpos.BarcodeQROptions(escpos.QRLevel.L, 6))
                    

                var escposCommands = doc.feed(5).cut().generateUInt8Array();

                // create ClientPrintJob
                var cpj = new JSPM.ClientPrintJob();

                // Set Printer info
                var myPrinter = new JSPM.InstalledPrinter($('#printerName').val());
                cpj.clientPrinter = myPrinter;

                // Set the ESC/POS commands
                cpj.binaryPrinterCommands = escposCommands;

                // Send print job to printer!
                cpj.sendToClient();
        });
    }
}

});












// let online = true;
// setInterval(function(){
//     let img = new Image();
//     img.onerror=function() {
//         online = false;
//     }
//     img.src="https://donuttello.com/img/donuttello-logo.png?rnd="+new Date().getTime();
// }, 3000);

</script>
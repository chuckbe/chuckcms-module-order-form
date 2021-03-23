<script>
$(document).ready(function() {

var cof_pos_active_location = $('#cof_pos_location').attr('data-active-location');

init();

$('body').on('click', '.locationDropdownSelect', function (event) {
    event.preventDefault();

    changeActiveLocation($(this));
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
    } else {
        product = getProductDetails(product_id)
        addToCart(product);
    }
    
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
    
    cart_id = getActiveCart();

    product = getProductDetailsFromCartItemElement(cart_id, $(this), false);
    addToCart(product);

    //product_id = product.id; //$(this).parents('.cof_cartProductListItem').first().attr('data-product-id');

});

$('body').on('click', '.cof_cartProductListItemSubtraction', function (event) {
    event.preventDefault();
    
    cart_id = getActiveCart();

    product = getProductDetailsFromCartItemElement(cart_id, $(this), -1);
    addToCart(product);

    //product_id = product.id; //$(this).parents('.cof_cartProductListItem').first().attr('data-product-id');

});

$('body').on('click', '.cof_cartTabRemove', function (event) {
    event.preventDefault();

    cart_id = $(this).parent().attr('data-cart-id');
    carts = getAllCartsFromStorage();

    if (carts.length > 1) {
        removeCartTab(cart_id);
        removeCartFromStorage(cart_id);
        $('.cof_cartTabListLink:first').trigger('click');
    } else if(carts.length == 1) {
        removeCartFromStorage(cart_id);
        restoreCartsFromStorage();
    }
});

    



$('body').on('click', '#options-form .options_modal_item_radio label.form-check-label', function (event) {
    $(this).siblings('input').first().prop('checked', true);
});

//to-do: betalen button
$('body').on('click', '.betaalArea #cof_placeOrderBtnNow', function (event) {
    event.preventDefault();
    openPaymentModel(getActiveCart());
});






/* INIT */
function init() {
    restoreCartsFromStorage();

    calculateProductAvailability();
}
/* END OF INIT */



/* LOCATION FUNCTIONS */
function changeActiveLocation(elem) {
    let locationId = elem.attr('data-location-id');
    let locationName = elem.text();
    $('#cof_pos_location').attr('data-active-location', locationId);
    $('#cof_pos_location').text(locationName);
    cof_pos_active_location = locationId;
    calculateProductAvailability();
}
/* END OF LOCATION FUNCTIONS */



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

    resetCartTab(cartId);


    carts = getAllCartsFromStorage();
}

function removeCartTab(cartId) {
    $('.cof_cartTabListLink[data-cart-id='+cartId+']').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').remove();
}

function removeCartFromStorage(cartId) {
    carts = getAllCartsFromStorage();

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

        //$('#cof_emptyCartNotice').hide();
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

    calculateTotalPrice(cart_id);
}

function addProductToCartInStorage(product, cartId) {
    carts = getAllCartsFromStorage();

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
                total_price: product.total_price
            });

            carts[i].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            return;
        }
    };
}

function removeProductToCartInStorage(product, cartId) {
    carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            products = carts[g].products;

            for (var i = 0; i < products.length; i++) {
                if (products[i].id == product.id && products[i].attribute == product.attribute && JSON.stringify(products[i].options) == JSON.stringify(product.options) && JSON.stringify(products[i].extras) == JSON.stringify(product.extras) ) {
                    productIndex = i;
                }
            };
            products.splice(productIndex, 1);

            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            return;
        }
    };
}

function updateProductToCartInStorage(product, cartId) {
    carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            products = carts[g].products;

            for (var i = 0; i < products.length; i++) {
                if (products[i].id == product.id && products[i].attribute == product.attribute && JSON.stringify(products[i].options) == JSON.stringify(product.options) && JSON.stringify(products[i].extras) == JSON.stringify(product.extras) ) {
                    products[i].quantity = parseInt(products[i].quantity) + parseInt(product.quantity);
                    products[i].total_price = parseInt(products[i].total_price) + parseInt(product.total_price);
                }
            };

            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
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
    carts = getAllCartsFromStorage();
    og_cart_id = $('.cof_cartTabListLink:first').attr('data-cart-id');

    for (var i = 0; i < carts.length; i++) {
        cartId = carts[i].id;
        addNewCartTabLink(cartId);
        addNewCartTab(cartId);
        activateCart(cartId);
        addProductsToCartFromStorage(cartId);
    };

    if(carts.length == 0) {
        cartId = getNewCartId();
        addNewCartTabLink(cartId);
        addNewCartTab(cartId);
        storeNewCart(cartId);
        $('.cof_cartTabListLink[data-cart-id='+cartId+']').trigger('click');
        //activateCart(cartId);
    }

    removeCartTab(og_cart_id);
}

function addProductsToCartFromStorage(cartId) {
    products = getProductsForCartFromStorage(cartId);

    for (var i = 0; i < products.length; i++) {
        addToCart(products[i], cartId);
    };
}

function getProductsForCartFromStorage(cartId) {
    carts = getAllCartsFromStorage();
    products = [];

    for (var i = 0; i < carts.length; i++) {
        if (carts[i].id == cartId) {
            products = carts[i].products;
        }
    };

    return products;
}

function storeNewCart(cartId) {
    carts = getAllCartsFromStorage();

    cart = {
        id: cartId,
        active: true,
        products: [],
        subtotal: 0,
        discount: 0,
        vat: 0,
        total: 0
    }

    carts.push(cart);
    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

/* END OF CART FUNCTIONS */



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
        current_price: Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')),
        total_price: Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')) * (quantity)
    }

    return product;
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



/* PRICE FUNCTIONS */
function calculateTotalPrice(cart_id) {
    total_price = getTotalPrice(cart_id);
    shipping_price = 0;//calculateShippingPrice();

    total_with_shipping_price = total_price + shipping_price;
    $('.cof_cartTotalPrice').text('€ '+parseFloat(total_with_shipping_price).toFixed(2).replace('.', ','));

    // has_mop = $('#cof_orderBtnCard').attr('data-has-mop');
    // if(has_mop == true){
    //     mop = $('#cof_orderBtnCard').attr('data-mop');
    //     if(total_price >= mop) {
    //         $('#cof_minOrderP_not').hide();
    //         $('#cof_placeOrderBtnNow').prop('disabled', false);
    //     } else {
    //         $('#cof_minOrderP_not').show();
    //         $('#cof_placeOrderBtnNow').prop('disabled', true);
    //     }
    // }
}

function getTotalPrice(cart_id) {
    total_price = 0;
    $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem').each(function() {
        total_price = total_price + parseFloat($(this).attr('data-total-price'));
    });
    return total_price;
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

function formatPrice(raw) {
    return '€ '+raw.toFixed(2).replace('.', ',')
}
/* END OF PRICE FUNCTIONS */



function openPaymentModel(cartId) {
    carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if( carts[i].id == cartId) {
            /*let products = carts[i].products;
            let price = getTotalPrice(cartId)
            let shipping = 0;*/
            console.log(carts[i]);
        }
    };
    
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

    if(attribute_name == '' || attribute_name == undefined) {
        full_name = product_name;
    } else {
        full_name = product_name + ' - ' + attribute_name;
    }

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
    
    new_total_price = parseFloat(productListItem.attr('data-total-price')) + parseFloat(total_price);
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
            $('.attributes_modal_item_button:last').find('.attributes_modal_item_button_text').attr('id', 'attribute'+i).text(attribute_name+(attribute_price !== null ? ' (€ '+attribute_price+')' : ''));

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

});



















































































// let online = true;
// setInterval(function(){
//     let img = new Image();
//     img.onerror=function() {
//         online = false;
//     }
//     img.src="https://donuttello.com/img/donuttello-logo.png?rnd="+new Date().getTime();
// }, 3000);

// var cart = [];
// if (localStorage.getItem("cart") !== null) {
//   let storedCart = JSON.parse(localStorage.getItem('cart'));
//   cart = storedCart;
//   console.log(storedCart);
//   storedCart.forEach((billItem)=> {
//       let bestelNavigation = $('#bestelNavigationTab');
//       let $tab = $(`<a class="flex-sm-fill text-sm-center nav-link" id="bestelNavigation${billItem.rekening}Tab" href="#${billItem.rekening}" role="tab" data-toggle="tab" aria-controls="${billItem.rekening}Tab" aria-selected="true" data-bestel-id="${billItem.rekening}"><span>bestelcode: #${(billItem.rekening).match(/\d/g).join("")}</span><span class="remove-tab"><i class="fas fa-times-circle"></i></span></a>`);
//       let $tabPane = $(`<div class="tab-pane fade show" id="${billItem.rekening}"  role="tabpanel" aria-labelledby="${billItem.rekening}Tab" data-bestel-id="${billItem.rekening}"></div>`)  
//       $('#bestelNavigationTab #bestelNavigationnNieuweBestellingTab').after($tab);
//       $('#bestelNavigationTabContent').prepend($tabPane);
//       let bestelPane = $('#bestelNavigationTabContent').find(`[data-bestel-id='${billItem.rekening}']`);
//       if(bestelPane.attr("data-bestel-id") == billItem.rekening) {
//         bestelPane.empty();
//         billItem.products.map(function(product) {
//             let featured_img = '';
//             for( let key in product.productData.json.images) {
//                 if(product.productData.json.images[key].is_featured === true) {
//                     let url = window.location.protocol + "//" + location.host.split(":")[0];
//                     featured_img = url+product.productData.json.images[key].url.replace(" ","%20");
//                 }
//             }
//             let newOrder = 
//             $(`
//                 <div class="bestelOrder row align-items-center" data-product-id=${product.productData.id}>
//                     <div class="col-5 bestelOrderDetails">
//                         <div class="col bestelOrderImg">
//                             <img src="${featured_img}" class="img-fluid" alt="${product.productData.json.name.nl}">
//                         </div>
//                         <div class="col bestelOrderTitle">
//                             <span>${product.productData.json.name.nl}</span>
//                         </div> 
//                     </div>
//                     <div class="col-4 bestelOrderQuantity">
//                         <div class="bestelOrderQuantityControl trash">
//                             <div class="deletebtn">
//                                 ${(product.quantity > 1) ? '<i class="fas fa-minus"></i>': '<i class="fas fa-trash"></i>'}
//                             </div>
//                         </div>
//                         <input type="text" id="quantity_product${product.productData.id}" name="quantity" value="${product.quantity}">
//                         <div class="bestelOrderQuantityControl">
//                             <div class="addbtn"><i class="fas fa-plus"></i></div>
//                         </div>
//                     </div>
//                     <div class="col-3 bestelOrderPrice">
//                         € ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}
//                     </div>
//                 </div>
//             `);
//             //console.log(parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ","));
//             bestelPane.append(newOrder);
//         });
//       }
//       if(billItem.state === 'active') {
//           $('#bestelNavigationTab').children().removeClass("active");
//           $('#bestelNavigationTabContent').children().removeClass("active");
//           $tab.addClass("active");
//           $tabPane.addClass("active");
//           let amountCalc = [];
//           $('#bestelNavigationTabContent .active .bestelOrder').each(function(){
//               amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//           });
//           let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//           //console.log(total, amountCalc);
//           $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`)
//       }
//   });

// }


// let GenRandom =  {
//     Stored: [],
	
// 	Job: function(){
// 		let newId = Date.now().toString().substr(6); // or use any method that you want to achieve this string
		
//         if( !this.Check(newId) ){
//             this.Stored.push(newId);
//             return newId;
//         }
        
//         return this.Job();
// 	},
	
// 	Check: function(id){
// 		for( let i = 0; i < this.Stored.length; i++ ){
// 			if( this.Stored[i] == id ) return true;
// 		}
// 		return false;
// 	}
	
// };


// $(document).ready(function(){
//     $.ajax({
//         url: "{{ ChuckSite::getSite('domain') }}/dashboard/order-form/pos/data",
//         type: 'GET',
//         dataType: 'json', // added data type
//         success: function(res) {
//             localStorage.setItem('pos', JSON.stringify(res));        
//             function ucwords(str,force){
//               str=force ? str.toLowerCase() : str;  
//                 return str.replace(/(\b)([a-zA-Z])/g,
//                 function(firstLetter){
//                     return   firstLetter.toUpperCase();
//                 });
//             }
//             // READ STRING FROM LOCAL STORAGE
//             let retrievedObject = localStorage.getItem('pos');

//             // CONVERT STRING TO REGULAR JS OBJECT
//             let parsedObject = JSON.parse(retrievedObject);

//             // ACCESS DATA
//             if(parsedObject.locations.length > 0) {
//                 parsedObject.locations.forEach((location,locationIndex)=> {
//                     $('.cof_pos_location').attr('data-location-id', location.id);
//                     $('.cof_pos_location').text(location.json.name);
//                     return;
//                 });
//             }


//             var ogLocation = $('.cof_pos_location').first().attr('data-location-id');


//             if(parsedObject.collections.length > 0) {
//                 $('#navigationTab').empty();
//                 parsedObject.collections.forEach((category,categoryIndex)=> {
//                     $('#navigationTab').append(`<li class="nav-item mr-3"><a class="nav-link ${categoryIndex == 0 ? 'active' : ''}" id="navtiagtion${ucwords(category.json.name.toLowerCase())}Tab" href="#${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')}" role="tab" data-toggle="tab" aria-controls="donutsTab" aria-selected="true">${category.json.name}</a></li>`)
//                 });
//             }

//             if(parsedObject.products.length > 0) {
//                 $('#navigationTabContent').empty();
//                 parsedObject.collections.forEach((category,categoryIndex)=> {
//                     let $tabpanel = $(`<div class="tab-pane fade show ${categoryIndex == 0 ? 'active' : ''}" id="${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')}" role="tabpanel" aria-labelledby="${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')}Tab"></div>`);
//                     $('#navigationTabContent').append($tabpanel);
//                     $(`.tab-pane#${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')}`).append(`<div class="row" id="row${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')}"></div>`);
//                     parsedObject.products.forEach((product)=> {
//                         if(product.json.category == category.id) {
                            
//                             let $card = createProductCard(product, ogLocation);

//                             $(`.tab-pane#${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')} #row${category.json.name.replace(/[^\w ]+/g,'').replace(/ +/g,'-')}`).append($card);
//                         }
//                     });
//                 });
//             }
//         }
//     });
    
//     let checkExist = setInterval(function() {
//         if ($('.posproduct').length) {
//             $( ".posproduct" ).each(function(index) {
//                 $(this).on("click", function(){
//                     let id = $(this).data('pid');
//                     addToCart(id);
//                 });
//             });
//             clearInterval(checkExist);
//         }
//     }, 100); // check every 100ms

//     // bestel navigation system
//     if($("#bestelNavigationTab").children().length < 2){
//         let $randomBestelCode = GenRandom.Job();
//         let $newTab = $(`<a class="flex-sm-fill text-sm-center nav-link active" id="bestelNavigationbestelcode${$randomBestelCode}Tab" href="#bestelcode${$randomBestelCode}" role="tab" data-toggle="tab" aria-controls="bestelcode${$randomBestelCode}Tab" aria-selected="true" data-bestel-id="bestelcode${$randomBestelCode}"><span>bestelcode: #${$randomBestelCode}</span><span class="remove-tab"><i class="fas fa-times-circle"></i></span></a>`)
//         let $newTabPane = $(`<div class="tab-pane fade show active" id="bestelcode${$randomBestelCode}"  role="tabpanel" aria-labelledby="bestelcode${$randomBestelCode}Tab" data-bestel-id="bestelcode${$randomBestelCode}"></div>`)
//         $('#bestelNavigationTab #bestelNavigationnNieuweBestellingTab').after($newTab);
//         $('#bestelNavigationTabContent').prepend($newTabPane);
//         cart.push({
//             'rekening': `bestelcode${$randomBestelCode}`,
//             'state': 'active',
//             'products': []
//         });
//         localStorage.setItem('cart', JSON.stringify(cart));
//     };

//     // creates new tab
//     $('#bestelNavigationnNieuweBestellingTab').on("click", function(e) {
//         e.preventDefault();
//         $('#bestelNavigationTab').children().removeClass("active");
//         $('#bestelNavigationTabContent').children().removeClass("active");
//         let $randomBestelCode = GenRandom.Job();
//         let $newTab = $(`<a class="flex-sm-fill text-sm-center nav-link active" id="bestelNavigationbestelcode${$randomBestelCode}Tab" href="#bestelcode${$randomBestelCode}" role="tab" data-toggle="tab" aria-controls="bestelcode${$randomBestelCode}Tab" aria-selected="true" data-bestel-id="bestelcode${$randomBestelCode}"><span>bestelcode: #${$randomBestelCode}</span><span class="remove-tab"><i class="fas fa-times-circle"></i></span></a>`)
//         let $newTabPane = $(`<div class="tab-pane fade show active" id="bestelcode${$randomBestelCode}"  role="tabpanel" aria-labelledby="bestelcode${$randomBestelCode}Tab" data-bestel-id="bestelcode${$randomBestelCode}"></div>`)
//         $('#bestelNavigationTab #bestelNavigationnNieuweBestellingTab').after($newTab);
//         $('#bestelNavigationTabContent').prepend($newTabPane);
//         cart.forEach((cartItem,cartIndex)=>{
//             cartItem.state = 'inactive';
//         })
//         cart.push({
//             'rekening': `bestelcode${$randomBestelCode}`,
//             'state': 'active',
//             'products': []
//         });
//         localStorage.setItem('cart', JSON.stringify(cart));
//         let activeRekeningId = `bestelcode${$randomBestelCode}`;
//         let amountCalc = [];
//         $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${activeRekeningId}] .bestelOrder`).each(function(){
//             amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//         });
//         let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//         $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`)
//     });
    
//     // removes products
//     $(document).on("click","#bestelNavigationTab .nav-link .remove-tab",function(event) {
//         event.preventDefault();
//         event.stopPropagation();
//         let $tab = $(this).parent();
//         let tabpaneid = $($tab).prop('href').split('#')[1];
//         let $nextTab = $tab.next('.nav-link');
//         let $prevTab = $tab.prev('.nav-link');
//         let $activeBestelId = ($nextTab.length == 0) ? $prevTab.attr('data-bestel-id') : $nextTab.attr('data-bestel-id');
//         if($nextTab.length == 0){
//             let $prevTabPane = $(`#bestelNavigationTabContent #${tabpaneid}`).prev('.tab-pane');
//             if($prevTab.attr('data-target') != 'nieuweBestelling'){
//                 $prevTab.addClass("active");
//                 $prevTabPane.addClass("active");
//             }
//         }else{
//             let $nextTabPane = $(`#bestelNavigationTabContent #${tabpaneid}`).next('.tab-pane');
//             $nextTab.addClass("active");
//             $nextTabPane.addClass("active");
//         }
//         $(`#bestelNavigationTabContent #${tabpaneid}`).remove();
//         $($tab).remove();
//         for (i = 0; i < cart.length; i++) {
//             if(cart[i].rekening == $activeBestelId){
//                 cart[i].state = 'active';
//             }
//             if(cart[i].rekening == `${tabpaneid}`){
//                 cart = cart.filter(item => item !== cart[i]) //remove element from array;
//             }
//         }
//         console.log($activeBestelId);
//         localStorage.setItem('cart', JSON.stringify(cart));
//         let activeRekeningId = $activeBestelId;
//         let amountCalc = [];
//         $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${activeRekeningId}] .bestelOrder`).each(function(){
//             amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//         });
//         let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//         //console.log($('.priceCalculatorArea .st-value'));
//         //console.log(`€ ${total.toFixed(2).replace(".", ",")}`)
//         $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`);
//     });
//     // bestel navigation system ends

//     // bestel cart area
//     let local = localStorage.getItem('pos');
//     let localParsed = JSON.parse(local);
//     // add to cart btn on tapping the product
//     const addToCart = function(id) {
//         localParsed.products.forEach((product)=> {
//             if(product.id == id){
//                 if(!$.isEmptyObject(product.json.combinations)){
//                     console.log("product with combination");
//                     let $wrapper = $('.wrapper');
//                     let $modal = $(`<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
//                                         <div class="modal-dialog" role="document">
//                                             <div class="modal-content">
//                                                 <div class="modal-body">
//                                                     ...
//                                                 </div>
//                                                 <div class="modal-footer">
//                                                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
//                                                     <button type="button" class="btn btn-primary">Save changes</button>
//                                                 </div>
//                                             </div>
//                                         </div>
//                                     </div>`);
//                     $wrapper.append($modal);
//                     $('#exampleModal').modal('show');
//                 }else{
//                     let activeRekeningId = $("#bestelNavigationTabContent .tab-pane.active").attr('data-bestel-id');
//                     cart.forEach((cartItem)=>{
//                         if(cartItem.rekening == activeRekeningId){
//                             cartItem.state = 'active';
//                             if(cartItem.products.length === 0){
//                                 cartItem.products.push({
//                                     'productData': product,
//                                     'quantity': 1
//                                 });
//                                 localStorage.setItem('cart', JSON.stringify(cart));
//                             }else {
//                                 let isProductPresent = cartItem.products.some(el => el.productData.id === id);
//                                 if(isProductPresent){
//                                     cartItem.products.forEach((cartproduct)=>{
//                                         if(cartproduct.productData.id === id){
//                                             cartproduct.quantity = cartproduct.quantity+1;
//                                             localStorage.setItem('cart', JSON.stringify(cart));
//                                         }
//                                     });
//                                 }else{
//                                     cartItem.products.push({
//                                         'productData': product,
//                                         'quantity': 1
//                                     });
//                                     localStorage.setItem('cart', JSON.stringify(cart));
//                                 }
                                
//                             }
//                             localStorage.setItem('cart', JSON.stringify(cart));
//                         }else{
//                             cartItem.state = 'inactive';
//                             localStorage.setItem('cart', JSON.stringify(cart));
//                         }
                        
//                     });
//                 }
//             }
//         });
//         cart.forEach( cartItem=>{
//             let bestelPane = $('#bestelNavigationTabContent').find(`[data-bestel-id='${cartItem.rekening}']`);
//             if(bestelPane.hasClass('active')){
//                 if(bestelPane.attr("data-bestel-id") == cartItem.rekening) {
//                     bestelPane.empty();
//                     cartItem.products.map(function(product) {
//                         let featured_img = '';
//                         for( let key in product.productData.json.images) {
//                             if(product.productData.json.images[key].is_featured === true) {
//                                 let url = window.location.protocol + "//" + location.host.split(":")[0];
//                                 featured_img = url+product.productData.json.images[key].url.replace(" ","%20");
//                             }
//                         }
//                         let newOrder = 
//                         $(`
//                             <div class="bestelOrder row align-items-center" data-product-id=${product.productData.id}>
//                                 <div class="col-5 bestelOrderDetails">
//                                     <div class="col bestelOrderImg">
//                                         <img src="${featured_img}" class="img-fluid" alt="${product.productData.json.name.nl}">
//                                     </div>
//                                     <div class="col bestelOrderTitle">
//                                         <span>${product.productData.json.name.nl}</span>
//                                     </div> 
//                                 </div>
//                                 <div class="col-4 bestelOrderQuantity">
//                                     <div class="bestelOrderQuantityControl trash">
//                                         <div class="deletebtn">
//                                             ${(product.quantity > 1) ? '<i class="fas fa-minus"></i>': '<i class="fas fa-trash"></i>'}
//                                         </div>
//                                     </div>
//                                     <input type="text" id="quantity_product${product.productData.id}" name="quantity" value="${product.quantity}">
//                                     <div class="bestelOrderQuantityControl">
//                                         <div class="addbtn"><i class="fas fa-plus"></i></div>
//                                     </div>
//                                 </div>
//                                 <div class="col-3 bestelOrderPrice">
//                                     € ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}
//                                 </div>
//                             </div>
//                         `);
//                         bestelPane.append(newOrder);
//                         let activeRekeningId = cartItem.rekening;
//                         let amountCalc = [];
//                         $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${activeRekeningId}] .bestelOrder`).each(function(){
//                             amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//                         });
//                         let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//                         $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`);
//                     });
//                 }
//             }

//         });
//     }
    
//     //delete btn below
//     $(document).on('click', '.bestelOrderQuantityControl .deletebtn', function(event) {
//         let tab = $(this).parents()[3];
//         let orderId = $(tab).attr('id');
//         let productrow = $(this).parents()[2];
//         let productId = $(productrow).attr('data-product-id');
//         let bestelOrderQuantity = $(this).parent().siblings('input').val();
//         //console.log(bestelOrderQuantity);
//         if(bestelOrderQuantity <= 2){
//             let deletebtn = $(this);
//             deletebtn.html('<i class="fas fa-trash"></i>')
//         }
//         cart.forEach((cartItem)=>{
//             if(orderId == cartItem.rekening){
//                 cartItem.state = 'active';
//                 //console.log("delete this item: ",cartItem);
//                 cartItem.products.forEach((product)=>{
//                     if(product.productData.id == productId){
//                         //console.log("delete this item: ", product);
//                         if(product.quantity > 1){
//                             product.quantity = product.quantity - 1;
//                             localStorage.setItem('cart', JSON.stringify(cart));
//                             $(this).parent().siblings(`input#quantity_product${product.productData.id}`).val(product.quantity);
//                             let pricecontainer = $(productrow).children('.bestelOrderPrice');
//                             pricecontainer.text(`€ ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}`);
//                         } else{
//                             if(confirm("Are you sure you want to delete this?")){
//                                 cartItem.products = jQuery.grep(cartItem.products, function(value) {
//                                     return value != product;
//                                 });
//                                 ($(this).parents()[2]).remove();
//                                 localStorage.setItem('cart', JSON.stringify(cart));
//                             }
//                         }
//                         let activeRekeningId = cartItem.rekening;
//                         let amountCalc = [];
//                         $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${activeRekeningId}] .bestelOrder`).each(function(){
//                             amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//                         });
//                         let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//                         $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`);
//                     }
//                 });
//             }else{
//                 cartItem.state = 'inactive';
//             }
//             localStorage.setItem('cart', JSON.stringify(cart));
//         });
//     });

//     //add btn below
//     $(document).on('click', '.bestelOrderQuantityControl .addbtn', function(event){
//         let tab = $(this).parents()[3];
//         let orderId = $(tab).attr('id');
//         let productrow = $(this).parents()[2];
//         let productId = $(productrow).attr('data-product-id');
//         cart.forEach((cartItem)=>{
//             if(orderId == cartItem.rekening){
//                 cartItem.state = 'active';
//                 cartItem.products.forEach((product)=>{
//                     if(product.productData.id == productId){
//                         product.quantity = product.quantity + 1;
//                         localStorage.setItem('cart', JSON.stringify(cart));
//                         $(this).parent().siblings(`input#quantity_product${product.productData.id}`).val(product.quantity);
//                         let pricecontainer = $(productrow).children('.bestelOrderPrice');
//                         pricecontainer.text(`€ ${parseFloat(product.productData.json.price.final * product.quantity).toFixed(2).replace(".", ",")}`);
//                     }
//                 });
//                 let activeRekeningId = cartItem.rekening;
//                 let amountCalc = [];
//                 $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${activeRekeningId}] .bestelOrder`).each(function(){
//                     amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//                 });
//                 let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//                 $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`);
//             } else {
//                 cartItem.state = 'inactive';
//             }
//             localStorage.setItem('cart', JSON.stringify(cart));
//         });
//     });

//     // delete all btn
//     $(document).on('click', '.bestelHeaderInstellingen .deletealles', function(event){
//         $('#bestelNavigationTab').children().each(function () {
//             if(this.getAttribute('data-toggle') == 'tab'){
//                 let id = this.getAttribute('data-bestel-id');
//                 let tabpane = $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${id}]`);
//                 //console.log(tabpane, this);
//                 tabpane.remove();
//                 this.remove(); 
//                 cart = []
//                 localStorage.setItem('cart', JSON.stringify(cart));
//             }
//         });
//         $('.priceCalculatorArea .st-value').text(`€ 0,00`);
//     });
    
//     //active tab switcher
//     $(document).on('click', '#bestelNavigationTab a.nav-link[data-toggle="tab"]', function(event){
//         event.preventDefault();
//         let activeRekeningId = $(this).attr('data-bestel-id');
//         cart.forEach(cartItem=>{
//             if(cartItem.rekening === activeRekeningId){
//                 cartItem.state = 'active';
//             } else {
//                 cartItem.state = 'inactive'
//             }
//         });
//         localStorage.setItem('cart', JSON.stringify(cart));
//         let amountCalc = [];
//         $(`#bestelNavigationTabContent .tab-pane[data-bestel-id=${activeRekeningId}] .bestelOrder`).each(function(){
//             amountCalc.push(parseFloat($(this).find('.bestelOrderPrice').text().replace(',', '.').match(/[\d\.]+/)));
//         });
//         let total = amountCalc.reduce((pv,cv)=>{ return pv + (parseFloat(cv)||0) },0);
//         //console.log(total, amountCalc);
//         $('.priceCalculatorArea .st-value').text(`€ ${total.toFixed(2).replace(".", ",")}`)
//     });


//     // bestel cart area ends
//     $('#bestelNavigationTab a.nav-link[data-toggle="tab"]').change(function () {
//         console.log("something changes", this);
//     });



//     function getActiveLocation() {

//     }

//     function createProductCard(product, ogLocation) {
//         let url = "{{ ChuckSite::getSite('domain') }}";
//         let logo_url = url+"{{ ChuckSite::getSetting('logo.href') }}";
//         let featured_img = '';
        
//         if(product.json.featured_image == null) {
//             featured_img = logo_url;
//         } else {
//             featured_img = url+product.json.featured_image;
//         }
        
//         return $(`<div class="col-3 p-1 posproduct ${(product.json.quantity[ogLocation] == 0) ? 'unavailable' : ''}" data-pid=${product.id}>
//                         <div class="card shadow-sm">
//                             <div class="card-body">
//                                 <h5 class="card-title">${product.json.name.nl}</h5>
//                                 <div class="row">
//                                     <div class="col">
//                                         <h6 class="card-subtitle mb-2 text-muted">€ ${parseFloat(product.json.price.final).toFixed(2).replace(".", ",")}</h6>
//                                         ${(product.json.quantity[ogLocation] == 0) ? '<p style="font-size: 10px; color: #e72870">Niet beschikbaar</p>' : ''}
//                                     </div>
//                                     <div class="col">
//                                         <img src=${featured_img} class="img-fluid" alt=${product.json.name.nl}>
//                                     </div>
//                                 </div>
//                             </div>
//                         </div>
//                     </div>`);
//     }
// });
</script>
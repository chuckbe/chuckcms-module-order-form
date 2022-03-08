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
                <button type="button" class="btn btn-primary btn-block" id="addProductWithOptionsToCartButton">Toevoegen</button>
            </div>
        </form>
    </div>
  </div>
</div>


<div class="modal fade" id="subproductModal" tabindex="-1" role="dialog" aria-labelledby="subproductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1140px;">
        <div class="modal-content">
            <form action="" id="subproduct-form">
                <div class="modal-header">
                    <div class="modal-img pr-3"><img src="" class="img-responsive" style="max-width:100px; max-height: 100px; object-fit: cover" alt="" /></div>
                    <div class="modal-title font-cera-bold" id="subproductModalLabel">
                        <h5 class="subproduct_product_name"></h5>
                        <p class="subproduct_product_description"></p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container d-none" id="subproductModalBody" style="max-height: 50vh; overflow-y: scroll;">
                        <div class="row subproduct_group_modal_row py-3">
                            <div class="col-12 d-flex">
                                <h6 class="subproduct_product_group_label">Group 1 Label</h6>
                                <div class="d-flex ml-auto">
                                    <span><span class="subproduct_product_group_selected">0</span>/<span class="subproduct_product_group_max">0</span></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row subproduct_group_product_row pt-3">
                                    <div class="col col-xl-3 card subproduct_group_product py-3">
                                        <img src="https://donuttello.com/photos/shares/donuts/Donuttello-Selection-Kaneel.jpg">
                                        <div class="d-flex">
                                            <small class="subproduct_group_product_name">name here</small>
                                            <small class="text-muted d-none ml-auto product_extra_price"></small>
                                        </div>
                                        <div class="d-flex position-absolute top-0 w-100 px-3" style="right: -1px">
                                            <div class="d-flex subproduct_group_product_qty bg-light py-2 ml-auto" style="max-width: 100px">
                                                <div class="col d-flex flex-wrap justify-content-center">
                                                    <div class="reducebtn" style="cursor:pointer;">
                                                        <i class="fas fa-minus"></i>
                                                    </div>
                                                </div>
                                                <div class="col col-md-4 px-0">
                                                    <input type="text" class="p-0 m-0 w-100 text-center border-0 product_qty" name="quantity" readonly="" value="0">
                                                </div>
                                                <div class="col d-flex flex-wrap justify-content-center">
                                                    <div class="addbtn" style="cursor:pointer;"><i class="fas fa-plus"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex w-100">
                        <div class="col">
                            <span id="subproduct_group_total_price"></span>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-block" id="addProductWithSubproductToCartButton">Toevoegen</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
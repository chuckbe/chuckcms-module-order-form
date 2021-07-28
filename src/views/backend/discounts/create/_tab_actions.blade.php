<div class="form-group row required">
    <label for="action_type" class="col-sm-2 col-form-label">Type *</label>
    <div class="col-sm-10">
        <select class="custom-select action_type_input" id="action_type" name="action_type" required>
            <option value="percentage">Percentage</option>
            <option value="currency">Bedrag</option>
        </select>
    </div>
</div>
<div class="form-group row required">
    <label for="action_value" class="col-sm-2 col-form-label">Hoeveelheid *</label>
    <div class="col-sm-10">
        <input type="number" min="0" class="form-control" id="action_value" name="action_value" value="{{ old('action_value', '0') }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="action_value" class="col-sm-2 col-form-label">Toepassen op *</label>
    <div class="col-sm-10">
        <label for="apply_on_cart" class="d-block">
            <input type="radio" name="apply_on" id="apply_on_cart" value="cart" checked> Winkelwagen
        </label>
        <label for="apply_on_product" class="d-block">
            <input type="radio" name="apply_on" id="apply_on_product" value="product"> Specifiek product (zonder extras)
        </label>
        <label for="apply_on_conditions" class="d-block">
            <input type="radio" name="apply_on" id="apply_on_conditions" value="conditions"> Geselecteerde product(en)
        </label>
    </div>
</div>
<div class="form-group row required d-none" id="actions_apply_products_row">
    <label for="apply_product" class="col-sm-2 col-form label">Selecteer product</label>
    <div class="col-sm-10">
        <select class="custom-select apply_product_input" id="apply_product" name="apply_product" required disabled>
            @foreach(ChuckRepeater::for(config('chuckcms-module-order-form.products.slug')) as $product)
            <option value="{{ $product->id }}" data-type="product" class="">[product] {{ $product->name[app()->getLocale()] }}</option>
            @endforeach
        </select>
    </div>
</div>
<hr>
<div class="form-group row required">
    <label for="action_value" class="col-sm-2 col-form-label">Verwijder incompatibele korting(en)? *</label>
    <div class="col-sm-10">
        <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="remove_incompatible">
        <label for="discount_remove_incompatible">
            <input type="checkbox" class="boolean_checkbox_input" id="discount_remove_incompatible" value="1" name="remove_incompatible" /> Verwijder incompatibele korting(en) onmiddellijk uit winkelwagen?
        </label>
    </div>
</div>
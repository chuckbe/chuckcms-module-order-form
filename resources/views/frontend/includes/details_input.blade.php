<div class="col-md-8 order-md-1 order-sm-1 order-1">
	<label for="bestelling">Gegevens</label><br>
	<div class="row">
		@foreach(ChuckRepeater::for(config('chuckcms-module-order-form.locations.slug'))->sortBy('json.order') as $location)
		<div class="col col-xl-6">
			<div class="form-group">
                <label><input type="radio" class="cof_location_radio" 
                    data-location-key="{{ $location->id }}" 
                    data-first-available-date="{{ ChuckModuleOrderForm::firstAvailableDate($location->id) }}" 
                    data-days-of-week-disabled="{{ is_null($location->days_of_week_disabled) ? '' : $location->days_of_week_disabled }}" 
                    data-dates-disabled="{{ is_null($location->dates_disabled) ? '' : $location->dates_disabled }}" 
                    data-location-type="{{ $location->type }}" 
                    data-delivery-cost="{{ is_null($location->delivery_cost) ? 0 : $location->delivery_cost }}" 
                    data-delivery-free-from="{{ is_null($location->delivery_free_from) ? 0 : $location->delivery_free_from }}" 
                    data-time-required="{{ is_null($location->time_required) ? 0 : $location->time_required }}" 
                    data-time-min="{{ $location->time_min }}" 
                    data-time-max="{{ $location->time_max }}" 
                    data-time-default="{{ $location->time_default }}" 
                    name="location" value="{{ $location->id }}" {{ $loop->first ? 'checked' : '' }}> {{ $location->name }}</label><br>
	        </div>
		</div>
		@endforeach
	</div>

	<div class="row">
		<div class="col-sm-12 cof_datepicker_group">
			<div class="form-group">
				<label for="">Datum* </label><br>
                <input type="text" class="form-control cof_datepicker" name="order_date" id="order_date" readonly required>
	        </div>
		</div>
		<div class="col-sm-4 d-none hidden cof_datetimepicker_group">
			<div class="form-group">
				<label for="">Tijd* </label><br>
                <input type="text" class="form-control cof_datetimepicker datetimepicker-input" name="order_time" value="00:00" id="order_time" data-toggle="datetimepicker" data-target="#order_time" readonly required>
	        </div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-6 col-6">
			<div class="form-group">
				<label for="surname">Voornaam*</label>
                <input type="text" class="form-control" name="order_surname" id="surname" required> 
                @if ($errors->has('surname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('surname') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-6 col-6">
			<div class="form-group">
				<label for="name">Achternaam*</label>
                <input type="text" class="form-control" name="order_name" id="name" required> 
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
	</div> <!-- /.row -->

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="email">Email*</label>
                <input type="email" class="form-control" name="order_email" id="email" required> 
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-6">
			<div class="form-group">
				<label for="tel">Telefoonnummer</label>
                <input type="phone" class="form-control" name="order_tel" id="tel"> 
                @if ($errors->has('tel'))
                    <span class="help-block">
                        <strong>{{ $errors->first('tel') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
	</div> <!-- /.row -->

    <div class="row">
        <div class="col-12">
            <label>Leveringsadres</label>
        </div>
    </div>

	<div class="row">
		<div class="col-sm-4 col-8">
			<div class="form-group">
				<label for="street">Straat*</label>
                <input type="text" class="form-control" name="order_street" id="street" required> 
                @if ($errors->has('street'))
                    <span class="help-block">
                        <strong>{{ $errors->first('street') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-2 col-4">
			<div class="form-group">
				<label for="housenumber">Nr*</label>
                <input type="text" class="form-control" name="order_housenumber" id="housenumber" required> 
                @if ($errors->has('housenumber'))
                    <span class="help-block">
                        <strong>{{ $errors->first('housenumber') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-2 col-4">
			<div class="form-group">
				<label for="postalcode">Postcode*</label>
                <input type="text" class="form-control" name="order_postalcode" id="postalcode" required> 
                @if ($errors->has('zipcode'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zipcode') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-4 col-8">
			<div class="form-group">
				<label for="city">Gemeente*</label>
                <input type="text" class="form-control" name="order_city" id="city" required> 
                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
	</div> <!-- /.row -->

	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label for="remarks">Opmerkingen </label>
                <textarea class="form-control" name="order_remarks" id="remarks" rows="3"></textarea>
                @if ($errors->has('remarks'))
                    <span class="help-block">
                        <strong>{{ $errors->first('remarks') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
	</div> <!-- /.row -->

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="cof_orderInvoice" class="mb-0">
                    <input type="hidden" name="invoice" value="0">
                    <input type="checkbox" id="cof_orderInvoice" value="1" name="invoice" required> Ik heb een factuur nodig
                </label>
            </div>
        </div>
    </div>

    <div class="row cof_invoice_details_row d-none">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="company">Bedrijfsnaam</label>
                <input type="text" class="form-control" name="order_company" id="company"> 
                @if ($errors->has('company'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-6">
            <div class="form-group">
                <label for="vat">BTW-nummer</label>
                <input type="text" class="form-control" name="order_vat" id="vat"> 
                @if ($errors->has('vat'))
                    <span class="help-block">
                        <strong>{{ $errors->first('vat') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->

        <div class="col-sm-12">
            <div class="form-group mb-2">
                <label for="cof_orderInvoiceAddress" class="mb-0">
                    <input type="hidden" name="invoice_address" value="0">
                    <input type="checkbox" id="cof_orderInvoiceAddress" value="1" name="invoice_address" required> Factuuradres is anders dan leveringsadres
                </label>
            </div>
        </div>
    </div>

    <div class="row cof_invoice_address_row d-none">
        <div class="col-12">
            <label>Factuuradres</label>
        </div>
        <div class="col-sm-4 col-8">
            <div class="form-group">
                <label for="invoice_street">Straat*</label>
                <input type="text" class="form-control" name="order_invoice_street" id="invoice_street" data-invoice-address> 
                @if ($errors->has('invoice_street'))
                    <span class="help-block">
                        <strong>{{ $errors->first('invoice_street') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-2 col-4">
            <div class="form-group">
                <label for="invoice_housenumber">Nr*</label>
                <input type="text" class="form-control" name="order_invoice_housenumber" id="invoice_housenumber" data-invoice-address> 
                @if ($errors->has('invoice_housenumber'))
                    <span class="help-block">
                        <strong>{{ $errors->first('invoice_housenumber') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-2 col-4">
            <div class="form-group">
                <label for="invoice_postalcode">Postcode*</label>
                <input type="text" class="form-control" name="order_invoice_postalcode" id="invoice_postalcode" data-invoice-address> 
                @if ($errors->has('invoice_postalcode'))
                    <span class="help-block">
                        <strong>{{ $errors->first('invoice_postalcode') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
        <div class="col-sm-4 col-8">
            <div class="form-group">
                <label for="invoice_city">Gemeente*</label>
                <input type="text" class="form-control" name="order_invoice_city" id="invoice_city" data-invoice-address> 
                @if ($errors->has('invoice_city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('invoice_city') }}</strong>
                    </span>
                @endif
            </div> <!-- /.form-group -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
</div>
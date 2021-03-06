<div class="col-md-8 order-md-1 order-sm-1 order-1">
	<label for="bestelling">Gegevens</label><br>
	<div class="row">
		@foreach(config('chuckcms-module-order-form.locations') as $key => $location)
		<div class="col">
			<div class="form-group">
                <label><input type="radio" class="cof_location_radio" data-location-key="{{ $key }}" data-first-available-date="{{ ChuckModuleOrderForm::firstAvailableDate($key) }}" data-days-of-week-disabled="{{ config('chuckcms-module-order-form.locations.'.$key.'.days_of_week_disabled') }}" data-location-type="{{ config('chuckcms-module-order-form.locations.'.$key.'.type') }}" data-delivery-cost="{{ config('chuckcms-module-order-form.locations.'.$key.'.delivery_cost') }}" data-time-required="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_required') }}" data-time-min="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_min') }}" data-time-max="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_max') }}" data-time-default="{{ config('chuckcms-module-order-form.locations.'.$key.'.time_default') }}" name="location" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}> {{ $location['name'] }}</label><br>
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
</div>
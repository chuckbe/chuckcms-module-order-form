<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="createLocationModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Maak een nieuwe <span class="semi-bold">locatie</span> aan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Vul de volgende velden aan om de locatie aan te maken.</p>
          @if($errors->any())
            @foreach ($errors->all() as $error)
              <p class="text-danger">{{ $error }}</p>
            @endforeach
          @endif
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.order_form.locations.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam</label>
                  <input type="text" id="create_location_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group form-group-default form-group-default-select2">
                  <label class="">Type?</label>
                  <select class="custom-select" id="create_location_type" name="type" data-placeholder="Selecteer een type" required>
                      <option value="takeout">Takeout</option>
                      <option value="delivery">Delivery</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group form-group-default">
                  <label>Dagen van de week uitgesloten</label>
                  <input type="text" id="create_location_days_of_week_disabled" name="days_of_week_disabled" class="form-control">
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group form-group-default required ">
                  <label>Is on-the-spot mogelijk?</label>
                  <div class="form-check">
                    <input type="hidden" value="0" name="on_the_spot">
                    <input type="checkbox" class="form-check-input" value="1" id="create_location_on_the_spot" name="on_the_spot">
                    <label class="form-check-label" for="on_the_spot">Ter plaatse eten is mogelijk</label>
                  </div>
                </div>
              </div>
            
              <div class="col-sm-12">
                <div class="form-group form-group-default">
                  <label>Uitgesloten Datums (dd/mm/yyyy,dd/mm/yyyy,...)</label>
                  <input type="text" id="create_location_dates_disabled" name="dates_disabled" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group form-group-default">
                  <label>Leveringskost</label>
                  <input type="text" min="0.00" steps="0.01" id="create_delivery_cost" name="delivery_cost" class="form-control" value="0" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group form-group-default form-group-default-select2">
                  <label class="">Levering beperkt tot?</label>
                  <select class="custom-select" id="create_delivery_limited_to" name="delivery_limited_to" data-placeholder="Selecteer een beperking">
                      <option value="null" selected>Geen</option>
                      <option value="postalcode">Postcode</option>
                      <option value="radius">Radius</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Levering Radius</label>
                  <input type="number" min="0" steps="1" id="create_delivery_radius" name="delivery_radius" class="form-control" value="0" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Levering Radius Vertrekkend Van (geschreven adres)</label>
                  <input type="text" id="create_delivery_radius_from" name="delivery_radius_from" class="form-control" value="{{ ChuckSite::getSetting('company.street').' '.ChuckSite::getSetting('company.housenumber').', '.ChuckSite::getSetting('company.postalcode').' '.ChuckSite::getSetting('company.city') }}" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Levering In Postcodes</label>
                  <input type="text" id="create_delivery_in_postalcodes" name="delivery_in_postalcodes" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required ">
                  <label>Tijdsaanduiding verplicht?</label>
                  <div class="form-check">
                    <input type="hidden" value="0" name="time_required">
                    <input type="checkbox" class="form-check-input" value="1" id="time_required" name="time_required">
                    <label class="form-check-label" for="time_required">Tijd verplicht</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group form-group-default">
                  <label>Minimum Tijd</label>
                  <input type="number" min="1" steps="1" max="24" id="create_time_min" name="time_min" class="form-control" value="1" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group form-group-default">
                  <label>Maximum Tijd</label>
                  <input type="number" min="1" steps="1" max="24" id="create_time_max" name="time_max" class="form-control" value="24" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Standaard Tijd (HH:mm)</label>
                  <input type="text" id="create_time_default" name="time_default" class="form-control" value="14:00">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group form-group-default">
                  <label>POS gebruikers (user_id,user_id,...)</label>
                  <input type="text" id="create_location_pos_users" name="pos_users" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Volgorde</label>
                  <input type="number" min="0" steps="1" max="9999" id="create_location_order" name="order" class="form-control" value="{{ ($locations->count() + 1) }}" required>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-md-12 m-t-10 sm-m-t-10">
            <input type="hidden" name="create">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
            <button type="submit" class="btn btn-primary m-t-5 pull-right">Aanmaken</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
</div>
<!-- /.modal-dialog -->
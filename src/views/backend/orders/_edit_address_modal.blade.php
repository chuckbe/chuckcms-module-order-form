<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editAddressModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
        </button>
        <h5>Wijzig het adres van de bestelling</h5>
      </div>
      <div class="modal-body">
        <form role="form" method="POST" action="{{ route('dashboard.module.order_form.products.update_address') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group form-group-default required">
                  <label>Straat</label>
                  <input type="text" class="form-control" name="street" value="{{ $order->entry['street'] }}" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group form-group-default required">
                  <label>Huisnummer</label>
                  <input type="text" class="form-control" name="housenumber" value="{{ $order->entry['housenumber'] }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group form-group-default required">
                  <label>Postcode</label>
                  <input type="text" class="form-control" name="postalcode" value="{{ $order->entry['postalcode'] }}" required>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group form-group-default required">
                  <label>Gemeente</label>
                  <input type="text" class="form-control" name="city" value="{{ $order->entry['city'] }}" required>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-md-4 m-t-10 sm-m-t-10 pull-right">
            <input type="hidden" id="edit_order_id" name="edit_order_id" value="{{ $order->id }}">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="submit" class="btn btn-primary btn-block m-t-5">Bewerken</button>
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
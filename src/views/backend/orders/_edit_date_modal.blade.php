<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editDateModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h6>Wijzig de datum van de bestelling</h6>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" method="POST" action="{{ route('dashboard.module.order_form.products.update_date') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Datum (dd/mm/yyyy)</label>
                  <input type="text" class="form-control" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-week-start="1" name="order_date" value="{{ $order->entry['order_date'] }}" required>
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
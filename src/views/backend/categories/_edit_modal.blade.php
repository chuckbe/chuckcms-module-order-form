<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Bewerk de volgende <span class="semi-bold">categorie</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Bewerk de volgende velden om de categorie te wijzigen.</p>
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.order_form.categories.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam</label>
                  <input type="text" id="edit_category_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required ">
                  <label>Deze categorie tonen</label>
                  <div class="form-check">
                    <input type="hidden" value="0" name="is_displayed">
                    <input type="checkbox" class="form-check-input" value="1" id="edit_category_is_displayed" name="is_displayed">
                    <label class="form-check-label" for="edit_category_is_displayed">Weergeven</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Volgorde</label>
                  <input type="number" min="0" steps="1" max="9999" id="edit_category_order" name="order" class="form-control" required>
                </div>
              </div>
            </div>
            
          </div>
        <div class="row">
          <div class="col-md-12 m-t-10 sm-m-t-10 pull-right">
            <input type="hidden" id="edit_category_id" name="id" value="">
            <input type="hidden" name="update">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
            <button type="submit" class="btn btn-primary float-right">Bewerken</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
</div>
<style>
  .select2-dropdown {z-index:9999;}
</style>
<!-- /.modal-dialog -->
<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editRewardModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Bewerk de volgende <span class="semi-bold">beloning</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Bewerk de volgende velden om de beloning te wijzigen.</p>
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.order_form.rewards.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam</label>
                  <input type="text" id="edit_reward_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Punten</label>
                  <input type="number" min="0" steps="1" max="9999" id="edit_reward_points" name="points" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Afbeelding</label>
                  <div class="input-group">
                    <span class="input-group-btn">
                      <a id="lfm" data-input="edit_reward_logo" data-preview="editlogoholder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                        <i class="fa fa-picture-o"></i> Upload
                      </a>
                    </span>
                    <input id="edit_reward_logo" name="image" class="img_lfm_input form-control" accept="image/x-png" type="text">
                  </div>
                  <img id="editrewardholder" src="" style="margin-top:15px;max-height:100px;">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Selecteer korting</label>
                  <select class="custom-select" id="edit_reward_discount" name="discount" required>
                        @foreach(ChuckRepeater::for(config('chuckcms-module-order-form.discounts.slug')) as $discount)
                        @if($discount->type == "gift")
                        <option value="{{ $discount->id }}">[discount] {{ $discount->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
              </div>
            </div>
            
          </div>
        <div class="row">
          <div class="col-md-12 m-t-10 sm-m-t-10 pull-right">
            <input type="hidden" id="edit_reward_id" name="id" value="">
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
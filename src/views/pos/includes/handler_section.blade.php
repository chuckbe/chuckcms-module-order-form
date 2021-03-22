<div class="handlerArea row">
    <div class="container pl-5 pr-5 pt-4">
        <div class="row">

            <div class="col-7 p-1">
                <div class="card shadow kassieriInfomatie">
                    <div class="card-body">
                      <div class="row pb-2 align-items-center">
                          <div class="col-7 m-0 py-1 px-3">
                            <p class="card-text mb-1">Kassier: {{ucwords(Auth::user()->name)}}</p>
                            <small><b>Locatie: </b><span class="cof_pos_location"></span></small>
                          </div>
                          <div class="col-5 m-0 py-0 px-3 d-flex justify-content-end">
                            <a href="{{URL::to('/logout')}}" class="btn">Ontkoppelen</a>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-5 p-1">
                <div class="card shadow promoInformatie">
                    <div class="card-body">{{-- 
                      <h5 class="card-title">Promo-code toevoegen</h5> --}}
                      <div class="row pb-3 align-items-center">
                        <div class="col-6">
                            <button class="btn w-100">Promo</button>
                        </div>{{-- 
                        <div class="col-4">
                            <button class="btn w-100">Promo</button>
                        </div>
                        <div class="col-4">
                            <button class="btn w-100">Promo</button>
                        </div> --}}
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header row">
    <div class="container d-flex">
        <div class="col-3">
            <img class="logo" alt="logo" src="{{asset('chuckbe/chuckcms-module-order-form/donuttello-logo.png')}}"/>
        </div>
        <div class="col-9 headerSearchArea d-flex justify-content-end">
            {{-- <div class="text-right d-flex justify-content-end align-items-center h-100">
                <form action="#">
                    <input type="search" id="headerSearch" name="headerSearch">
                    <button type="submit" class="btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div> --}}
            <div class="text-right d-flex justify-content-end align-items-center h-100">
                <div class="locationTypeSwitcherWrapper {{ $locations->first()->type == 'delivery' ? 'd-none' : '' }}">
                    <div class="custom-control custom-switch mt-2">
                        <label for="locationTypeSwitcher" class="d-inline-block mr-4">Afhalen</label>
                        <input type="checkbox" class="custom-control-input" id="locationTypeSwitcher" @if(!$locations->first()->on_the_spot) disabled @endif>
                        <label class="custom-control-label ml-3 mr-3" for="locationTypeSwitcher">Dine-in</label>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light align-self-center dropdown-toggle" type="button" id="locationDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="cof_pos_location" data-active-location="{{ $locations->first()->id }}" data-type="{{ $locations->first()->type }}" data-on-the-spot="{{ $locations->first()->type == 'delivery' ? 0 : ($locations->first()->on_the_spot ? 1 : 0) }}" data-pos-name="{{ $locations->first()->pos_name }}" data-pos-address="{{ $locations->first()->pos_address1 }}" data-pos-address-t="{{ $locations->first()->pos_address2 }}" data-pos-vat="{{ $locations->first()->pos_vat }}" data-pos-receipt-title="{{ $locations->first()->pos_receipt_title }}" data-pos-receipt-footer-line="{{ $locations->first()->pos_receipt_footer_line1 }}" data-pos-receipt-footer-line-t="{{ $locations->first()->pos_receipt_footer_line2 }}"  data-pos-receipt-footer-line-tt="{{ $locations->first()->pos_receipt_footer_line3 }}">{{ $locations->first()->name }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="locationDropdownButton">
                        @foreach($locations as $location)
                        <a class="dropdown-item locationDropdownSelect" href="#" data-location-id="{{ $location->id }}" data-location-type="{{ $location->type }}" data-on-the-spot="{{ $location->type == 'delivery' ? 0 : ($location->on_the_spot ? 1 : 0) }}" data-pos-name="{{ $location->pos_name }}" data-pos-address="{{ $location->pos_address1 }}" data-pos-address-t="{{ $location->pos_address2 }}" data-pos-vat="{{ $location->pos_vat }}" data-pos-receipt-title="{{ $location->pos_receipt_title }}" data-pos-receipt-footer-line="{{ $location->pos_receipt_footer_line1 }}" data-pos-receipt-footer-line-t="{{ $location->pos_receipt_footer_line2 }}"  data-pos-receipt-footer-line-tt="{{ $location->pos_receipt_footer_line3 }}">{{ $location->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
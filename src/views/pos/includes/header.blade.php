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
                    <button class="btn btn-light align-self-center dropdown-toggle" type="button" id="locationDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="cof_pos_location" data-active-location="{{ $locations->first()->id }}" data-type="{{ $locations->first()->type }}" data-on-the-spot="{{ $locations->first()->type == 'delivery' ? 0 : ($locations->first()->on_the_spot ? 1 : 0) }}">{{ $locations->first()->name }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="locationDropdownButton">
                        @foreach($locations as $location)
                        <a class="dropdown-item locationDropdownSelect" href="#" data-location-id="{{ $location->id }}" data-location-type="{{ $location->type }}" data-on-the-spot="{{ $location->type == 'delivery' ? 0 : ($location->on_the_spot ? 1 : 0) }}">{{ $location->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-right d-flex justify-content-end align-items-center h-100">
                <button class="btn btn-light align-self-center ml-2" id="openPrintSettingsModal">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </div>
</div>
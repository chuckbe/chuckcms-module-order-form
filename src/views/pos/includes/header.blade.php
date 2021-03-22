<div class="header row">
    <div class="container d-flex">
        <div class="col-4">
            <img class="logo" alt="logo" src="{{asset('chuckbe/chuckcms-module-order-form/donuttello-logo.png')}}"/>
        </div>
        <div class="col-8 headerSearchArea d-flex justify-content-end">
            {{-- <div class="text-right d-flex justify-content-end align-items-center h-100">
                <form action="#">
                    <input type="search" id="headerSearch" name="headerSearch">
                    <button type="submit" class="btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div> --}}
            <div class="text-right d-flex justify-content-end align-items-center h-100">
                <div class="dropdown">
                    <button class="btn btn-light align-self-center dropdown-toggle" type="button" id="locationDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="cof_pos_location" data-active-location="{{ $locations->first()->id }}">{{ $locations->first()->name }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="locationDropdownButton">
                        @foreach($locations as $location)
                        <a class="dropdown-item locationDropdownSelect" href="#" data-location-id="{{ $location->id }}">{{ $location->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
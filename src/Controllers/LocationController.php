<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\LocationRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    private $locationRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function index()
    {
        $locations = $this->locationRepository->get();
        return view('chuckcms-module-order-form::backend.locations.index', compact('locations'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'type' => 'required|in:takeout,delivery',
            'days_of_week_disabled' => 'nullable',
            'dates_disabled' => 'nullable',
            'delivery_cost' => 'required|numeric|between:0,99.99',
            'delivery_free_from' => 'required|numeric|between:0,99.99',
            'delivery_limited_to' => 'in:null,postalcode,radius',
            'delivery_radius' => 'required|numeric',
            'delivery_radius_from' => 'required',
            'delivery_in_postalcodes' => 'nullable',
            'time_required' => 'required|in:0,1',
            'time_min' => 'required|numeric|between:1,24',
            'time_max' => 'required|numeric|between:1,24',
            'time_default' => 'required',
            'pos_users' => 'nullable',
            'pos_name' => 'required',
            'pos_address1' => 'required',
            'pos_address2' => 'nullable',
            'pos_vat' => 'required',
            'pos_receipt_title' => 'required',
            'pos_receipt_footer_line1' => 'nullable',
            'pos_receipt_footer_line2' => 'nullable',
            'pos_receipt_footer_line3' => 'nullable',
            'order' => 'numeric|required',
            'id' => 'required_with:update'
        ]);
        
        if($request->has('create')) {
            $location = $this->locationRepository->save($request);
        }

        if($request->has('id') && $request->has('update')) {
            $location = $this->locationRepository->update($request);
        } 

        if(!$location->save()){
            
            return 'error';//add ThrowNewException
        }

        return redirect()->route('dashboard.module.order_form.locations.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->locationRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.order_form.locations.index');
        }
    }

    
}

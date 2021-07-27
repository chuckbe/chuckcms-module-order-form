<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Models\Customer;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    private $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $customers = $this->customerRepository->get();
        return view('chuckcms-module-order-form::backend.customers.index', compact('customers'));
    }

    public function detail(Customer $customer)
    {
        return view('chuckcms-module-order-form::backend.customers.detail', compact('customer'));
    }

    public function save(Request $request)
    {
        // $this->validate($request, [ 
        //     'name' => 'max:185|required',
        //     'type' => 'required|in:takeout,delivery',
        //     'days_of_week_disabled' => 'nullable',
        //     'dates_disabled' => 'nullable',
        //     'delivery_cost' => 'required|numeric|between:0,99.99',
        //     'delivery_limited_to' => 'in:null,postalcode,radius',
        //     'delivery_radius' => 'required|numeric',
        //     'delivery_radius_from' => 'required',
        //     'delivery_in_postalcodes' => 'nullable',
        //     'time_required' => 'required|in:0,1',
        //     'time_min' => 'required|numeric|between:1,24',
        //     'time_max' => 'required|numeric|between:1,24',
        //     'time_default' => 'required',
        //     'pos_users' => 'nullable',
        //     'order' => 'numeric|required',
        //     'id' => 'required_with:update'
        // ]);
        
        // if($request->has('create')) {
        //     $location = $this->customerRepository->save($request);
        // }

        // if($request->has('id') && $request->has('update')) {
        //     $location = $this->customerRepository->update($request);
        // } 

        // if(!$location->save()){
            
        //     return 'error';//add ThrowNewException
        // }

        // return redirect()->route('dashboard.module.order_form.locations.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->customerRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.order_form.customers.index');
        }
    }

    
}

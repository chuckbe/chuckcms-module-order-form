<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CouponRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    private $couponRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function index()
    {
        $coupons = $this->couponRepository->get();
        return view('chuckcms-module-order-form::backend.coupons.index', compact('coupons'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'is_displayed' => 'required|in:0,1',
            'order' => 'numeric|required',
            'id' => 'required_with:update'
        ]);
        
        if($request->has('create')) {
            $category = $this->couponRepository->save($request);
        }

        if($request->has('id') && $request->has('update')) {
            $category = $this->couponRepository->update($request);
        } 

        if(!$category->save()){
            return 'error';//add ThrowNewException
        }

        return redirect()->route('dashboard.module.order_form.coupons.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->couponRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.order_form.coupons.index');
        }
    }

    
}

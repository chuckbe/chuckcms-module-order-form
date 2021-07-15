<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleOrderForm\Models\Coupon;
use Chuckbe\ChuckcmsModuleOrderForm\Models\Customer;
use Illuminate\Http\Request;

class CouponRepository
{
	private $coupon;

    private $repeater;

	public function __construct(Coupon $coupon, Repeater $repeater)
    {
        $this->coupon = $coupon;
        $this->repeater = $repeater;
    }

    /**
     * Get all the coupons
     *
     * @return \Illuminate\Support\Collection
     **/
    public function get()
    {
        return $this->coupon->get();
    }

    /**
     * Get all the coupons for the customer
     *
     * @param Customer $customer
     * 
     * @return \Illuminate\Support\Collection
     **/
    public function forCustomer(Customer $customer)
    {
        return $this->coupon->where('customer_id', $customer->id)->get();
    }

    public function save(Request $values)
    {
    	// $input = [];

    	// $input['slug'] = config('chuckcms-module-order-form.coupons.slug');
     //    $input['url'] = config('chuckcms-module-order-form.coupons.url').str_slug($values->get('name'), '-');
     //    $input['page'] = config('chuckcms-module-order-form.coupons.page');

    	// $json = [];
     //    $json['name'] = $values->get('name');
     //    $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
     //    $json['order'] = (int)$values->get('order');

     //    $input['json'] = $json;

     //    $of_category = $this->repeater->create($input);

     //    return $of_category;
    }

    public function update(Request $values)
    {
        // $of_category = $this->repeater->where('id', $values->get('id'))->firstOrFail();
        // $of_category->url = config('chuckcms-module-order-form.coupons.url').str_slug($values->get('name'), '-');

        // $json = [];
        // $json['name'] = $values->get('name');
        // $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        // $json['order'] = (int)$values->get('order');

        // $of_category->json = $json;

        // $of_category->update();

        // return $of_category;
    }

    public function delete(int $id)
    {
    	// $of_category = $this->repeater->where('slug', config('chuckcms-module-order-form.coupons.slug'))->where('id', $id)->first();
     //    if (is_null($of_category)) {
     //        return 'false';
     //    }
        
     //    if ($of_category->delete()) {
     //        return 'success';
     //    }

     //    return 'error';    
    }

    public function createFromReward(Repeater $reward, Customer $customer)
    {
        $input = [];

        $input['slug'] = config('chuckcms-module-order-form.coupons.slug');
        $input['url'] = config('chuckcms-module-order-form.coupons.url').str_slug($values->get('name'), '-');
        $input['page'] = config('chuckcms-module-order-form.coupons.page');

        $json = [];
        $json['name'] = $values->get('name');
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['order'] = (int)$values->get('order');

        $input['json'] = $json;

        $of_category = $this->repeater->create($input);

        return $of_category;
    }

}
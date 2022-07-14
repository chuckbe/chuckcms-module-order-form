<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DiscountRepository
{
    private $customerRepository;
    private $repeater;

	public function __construct(CustomerRepository $customerRepository, Repeater $repeater)
    {
        $this->customerRepository = $customerRepository;
        $this->repeater = $repeater;
    }

    /**
     * Get all the discounts
     *
     **/
    public function get()
    {
        return $this->query()->get();
    }

    /**
     * Find discount by code
     *
     **/
    public function code($code)
    {
        return $this->query()->where('json->code', $code)->first();
    }

    /**
     * Get the discount
     *
     * @var string
     **/
    public function getById($id)
    {
        if(!is_array($id)) {
            $id = [$id];
        }
        return $this->query()->whereIn('id', $id)->first();
    }

    /**
     * Create a new collection
     *
     * @var array $values
     **/
    public function create(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-order-form.discounts.slug');
        $input['url'] = config('chuckcms-module-order-form.discounts.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-order-form.discounts.page');
        
        $input['json']['name'] = $values->name;
        $input['json']['description'] = $values->description;
        $input['json']['code'] = $values->code;
        $input['json']['priority'] = $values->priority;
        $input['json']['highlight'] = $values->highlight == "1" ? true : false;
        $input['json']['active'] = $values->active == "1" ? true : false;

        $input['json']['customers'] = $values->customers ?? [];
        $input['json']['valid_from'] = $values->valid_from;
        $input['json']['valid_until'] = $values->valid_until;
        $input['json']['minimum'] = $values->minimum;
        $input['json']['minimum_vat_included'] = $values->minimum_vat_included == "1" ? true : false;
        $input['json']['minimum_shipping_included'] = $values->minimum_vat_included == "1" ? true : false;
        $input['json']['available_total'] = $values->available_total;
        $input['json']['available_customer'] = $values->available_customer;

        $input['json']['uncompatible_discounts'] = explode(',', ($values->uncompatible_discounts_concatenated ?? '')) ?? [];

        if (is_array($values->condition_min_quantity) && is_array($values->condition_type)) {
            foreach($values->condition_min_quantity as $mqKey => $min_quantity) {
                if (!array_key_exists($mqKey, array_values($values->condition_type)) 
                    || !array_key_exists($mqKey, array_values($values->condition_value))
                    || !is_array(array_values($values->condition_value)[$mqKey]) ) {
                    break;
                }

                $rules = [];
                foreach(array_values($values->condition_value)[$mqKey] as $ruleKey => $rule) {
                    $rules[] = array(
                        'type' => array_values($values->condition_type)[$mqKey][$ruleKey],
                        'value' => array_values($values->condition_value)[$mqKey][$ruleKey]
                    );
                }

                $input['json']['conditions'][] = array(
                    'min_quantity' => (int)$min_quantity,
                    'rules' => $rules
                );
            }
        } else {
            $input['json']['conditions'] = [];
        }

        $input['json']['type'] = $values->action_type;
        $input['json']['value'] = $values->action_value;
        $input['json']['remove_incompatible'] = $values->remove_incompatible == "1" ? true : false;
        $input['json']['apply_on'] = $values->apply_on;
        $input['json']['apply_product'] = $values->has('apply_product') ? $values->apply_product : null;

        $discount = $this->repeater->create($input);

        return $discount;
    }

    /**
     * Update an existing discount
     *
     * @var array $values
     **/
    public function update(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-order-form.discounts.slug');
        $input['url'] = config('chuckcms-module-order-form.discounts.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-order-form.discounts.page');
        
        $json = [];
        $json['name'] = $values->name;
        $json['description'] = $values->description;
        $json['code'] = $values->code;
        $json['priority'] = $values->priority;
        $json['highlight'] = $values->highlight == "1" ? true : false;
        $json['active'] = $values->active == "1" ? true : false;

        $json['customers'] = $values->customers ?? [];
        $json['valid_from'] = $values->valid_from;
        $json['valid_until'] = $values->valid_until;
        $json['minimum'] = $values->minimum;
        $json['minimum_vat_included'] = $values->minimum_vat_included == "1" ? true : false;
        $json['minimum_shipping_included'] = $values->minimum_vat_included == "1" ? true : false;
        $json['available_total'] = $values->available_total;
        $json['available_customer'] = $values->available_customer;

        $json['uncompatible_discounts'] = strlen($values->uncompatible_discounts_concatenated) > 0 ? explode(',', ($values->uncompatible_discounts_concatenated ?? '')) : [];

        if (is_array($values->condition_min_quantity) && is_array($values->condition_type)) {
            foreach($values->condition_min_quantity as $mqKey => $min_quantity) {
                if (!array_key_exists($mqKey, array_values($values->condition_type)) 
                    || !array_key_exists($mqKey, array_values($values->condition_value))
                    || !is_array(array_values($values->condition_value)[$mqKey]) ) {
                    break;
                }

                $rules = [];
                foreach(array_values($values->condition_value)[$mqKey] as $ruleKey => $rule) {
                    $rules[] = array(
                        'type' => array_values($values->condition_type)[$mqKey][$ruleKey],
                        'value' => array_values($values->condition_value)[$mqKey][$ruleKey]
                    );
                }

                $json['conditions'][] = array(
                    'min_quantity' => (int)$min_quantity,
                    'rules' => $rules
                );
            }
        } else {
            $json['conditions'] = [];
        } 

        $json['type'] = $values->action_type;
        $json['value'] = $values->action_value;
        $json['remove_incompatible'] = $values->remove_incompatible == "1" ? true : false;
        $json['apply_on'] = $values->apply_on;
        $json['apply_product'] = $values->has('apply_product') ? $values->apply_product : null;

        $discount = $this->repeater->where('id', $values->id)->first();
        $discount->slug = $input['slug'];
        $discount->url = $input['url'];
        $discount->page = $input['page'];
        $discount->json = $json;
        $discount->update();
        
        return $discount;
    }

    /**
     * Delete a collection
     *
     * @var int $id
     **/
    public function delete(int $id): bool
    {
        $this->repeater->destroy($id);
        return true;
    }

    /**
     * Get the query
     *
     **/
    private function query()
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.discounts.slug'));
    }

    public function checkValidity(Repeater $discount)
    {
        if(strtotime(now()) < strtotime($discount->valid_from)) {
            return false;
        }

        if(strtotime(now()) > strtotime($discount->valid_until)) {
            return false;
        }

        return true;
    }

    public function checkMinima(Repeater $discount, Cart $cart)
    {
        $cartValue = $cart->isTaxed ? ($cart->total() - $cart->tax()) : $cart->total();
        
        if($discount->minimum_vat_included) {
            $cartValue = $cart->isTaxed ? $cart->total() : ($cart->total() + $cart->tax());
        }

        if((float)$discount->minimum > (float)$cartValue) { 
            return false;
        }

        return true;
    }

    public function checkAvailability(Repeater $discount)
    {
        if($discount->available_total < 1) {
            return false;
        }

        return true;
    }

    public function checkAvailabilityForCustomer(Repeater $discount, $user_id)
    {
        if($discount->available_customer < 1) {
            return false;
        }

        if($discount->available_customer <= $this->timesUsedByUser($discount, $user_id)) {
            return false;
        }

        return true;
    }

    /**
     * Add usage tracking for discounts by customer
     *
     * @param  $discounts
     * @param  $customer
     * @return void
     **/
    public function addUsageByCustomer(array $discounts, $customer)
    {
        if(is_null($customer->user_id)) {
            return;
        }

        foreach($discounts as $discountKey => $cartDiscount) {
            $discount = $this->code($discountKey);
            $json = $discount->json;
            $json['available_total'] = (int)$json['available_total'] - 1;
            if(!array_key_exists('usage', $json)) {
                $json['usage'] = [];
            }
            $json['usage'][] = $customer->user_id;
            $discount->json = $json;
            $discount->update();
        }
    }

    private function timesUsedByUser(Repeater $discount, $user_id)
    {
        if(is_null($discount->usage)) {
            return 0;
        }

        if(in_array($user_id, $discount->usage)) {
            return array_count_values($discount->usage)[$user_id];
        }

        return 0;
    }

    public function checkConditions(Repeater $discount, Cart $cart)
    {
        if(is_null($discount->conditions)) {
            return true;
        }

        foreach($discount->conditions as $condition) {
            if(!$this->checkCondition($condition, $cart)) {
                return false;
            }
        }

        return true;
    }

    private function checkCondition($condition = [], Cart $cart)
    {
        if(!array_key_exists('type', $condition)) {
            return false;
        }

        if(!array_key_exists('value', $condition)) {
            return false;
        }

        switch ($condition['type']) {
            case 'collection':
                return $this->cartRepository->isCollectionPresent($cart, $condition['value']);
            case 'brand':
                return $this->cartRepository->isBrandPresent($cart, $condition['value']);
            case 'product':
                return $this->cartRepository->isProductPresent($cart, $condition['value']);
        }

        return false;
    }

    public function generateCode() 
    {
        $code = strtoupper(Str::random(8));
        $count = $this->query()->where('json->code', $code)->count();
        if ($count > 0) {
            $this->generateCode();
        } else {
            return $code;
        }
    }

}
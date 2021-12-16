<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;

class LocationRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the locations
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.locations.slug'))->get()->sortBy('json.order');
    }

    /**
     * Get all the locations for user
     *
     * @var string
     **/
    public function getForUser($user_id)
    {
        $locations = $this->repeater->where('slug', config('chuckcms-module-order-form.locations.slug'))->get();
        $locIds = [];
        foreach($locations as $key => $location) {
            $pos_users = explode(',', $location->pos_users);
            if (in_array($user_id, $pos_users)) {
                $locIds[] = $location->id;
            }
        }
        return $this->repeater->where('slug', config('chuckcms-module-order-form.locations.slug'))->whereIn('id', $locIds)->get()->sortBy('json.order');
    }

    public function save(Request $values)
    {
    	$input = [];

    	$input['slug'] = config('chuckcms-module-order-form.locations.slug');
        $input['url'] = config('chuckcms-module-order-form.locations.url').str_slug($values->get('name'), '-');
        $input['page'] = config('chuckcms-module-order-form.locations.page');

    	$json = [];
        $json['name'] = $values->get('name');
        $json['type'] = $values->get('type');
        $json['days_of_week_disabled'] = is_null($values->get('days_of_week_disabled')) ? '' : $values->get('days_of_week_disabled');
        $json['on_the_spot'] = ($values->get('on_the_spot') == '1' ? true : false);
        $json['dates_disabled'] = is_null($values->get('dates_disabled')) ? '' : $values->get('dates_disabled');
        
        $json['delivery_cost'] = (float)$values->get('delivery_cost');
        $json['delivery_free_from'] = (float)$values->get('delivery_free_from');
        $json['delivery_limited_to'] = $values->get('delivery_limited_to') == 'null' ? null : $values->get('delivery_limited_to');
        $json['delivery_radius'] = (int)$values->get('delivery_radius');
        $json['delivery_radius_from'] = $values->get('delivery_radius_from');
        $json['delivery_in_postalcodes'] = is_null($values->get('delivery_in_postalcodes')) ? array() : explode(',', $values->get('delivery_in_postalcodes'));

        $json['time_required'] = ($values->get('time_required') == '1' ? true : false);
        $json['time_min'] = (int)$values->get('time_min');
        $json['time_max'] = (int)$values->get('time_max');
        $json['time_default'] = $values->get('time_default');

        $json['pos_users'] = is_null($values->get('pos_users')) ? '' : $values->get('pos_users');
        $json['pos_name'] = $values->get('pos_name');
        $json['pos_address1'] = $values->get('pos_address1');
        $json['pos_address2'] = is_null($values->get('pos_address2')) ? '' : $values->get('pos_address2');
        $json['pos_vat'] = $values->get('pos_vat');
        $json['pos_receipt_title'] = $values->get('pos_receipt_title');

        $json['pos_receipt_footer_line1'] = is_null($values->get('pos_receipt_footer_line1')) ? '' : $values->get('pos_receipt_footer_line1');
        $json['pos_receipt_footer_line2'] = is_null($values->get('pos_receipt_footer_line2')) ? '' : $values->get('pos_receipt_footer_line2');
        $json['pos_receipt_footer_line3'] = is_null($values->get('pos_receipt_footer_line3')) ? '' : $values->get('pos_receipt_footer_line3');

        $json['order'] = (int)$values->get('order');

        $input['json'] = $json;

        $of_location = $this->repeater->create($input);

        return $of_location;
    }

    public function update(Request $values)
    {
        $of_location = $this->repeater->where('id', $values->get('id'))->firstOrFail();
        $of_location->url = config('chuckcms-module-order-form.locations.url').str_slug($values->get('name'), '-');

        $json = [];
        $json['name'] = $values->get('name');
        $json['type'] = $values->get('type');
        $json['days_of_week_disabled'] = is_null($values->get('days_of_week_disabled')) ? '' : $values->get('days_of_week_disabled');
        $json['on_the_spot'] = ($values->get('on_the_spot') == '1' ? true : false);
        $json['dates_disabled'] = is_null($values->get('dates_disabled')) ? '' : $values->get('dates_disabled');
        
        $json['delivery_cost'] = (float)$values->get('delivery_cost');
        $json['delivery_free_from'] = (float)$values->get('delivery_free_from');
        $json['delivery_limited_to'] = $values->get('delivery_limited_to') == 'null' ? null : $values->get('delivery_limited_to');
        $json['delivery_radius'] = (int)$values->get('delivery_radius');
        $json['delivery_radius_from'] = $values->get('delivery_radius_from');
        $json['delivery_in_postalcodes'] = is_null($values->get('delivery_in_postalcodes')) ? array() : explode(',', $values->get('delivery_in_postalcodes'));

        $json['time_required'] = ($values->get('time_required') == '1' ? true : false);
        $json['time_min'] = (int)$values->get('time_min');
        $json['time_max'] = (int)$values->get('time_max');
        $json['time_default'] = $values->get('time_default');

        $json['pos_users'] = is_null($values->get('pos_users')) ? '' : $values->get('pos_users');
        $json['pos_name'] = $values->get('pos_name');
        $json['pos_address1'] = $values->get('pos_address1');
        $json['pos_address2'] = is_null($values->get('pos_address2')) ? '' : $values->get('pos_address2');
        $json['pos_vat'] = $values->get('pos_vat');
        $json['pos_receipt_title'] = $values->get('pos_receipt_title');

        $json['pos_receipt_footer_line1'] = is_null($values->get('pos_receipt_footer_line1')) ? '' : $values->get('pos_receipt_footer_line1');
        $json['pos_receipt_footer_line2'] = is_null($values->get('pos_receipt_footer_line2')) ? '' : $values->get('pos_receipt_footer_line2');
        $json['pos_receipt_footer_line3'] = is_null($values->get('pos_receipt_footer_line3')) ? '' : $values->get('pos_receipt_footer_line3');

        $json['order'] = (int)$values->get('order');

        $of_location->json = $json;

        $of_location->update();

        return $of_location;
    }

    public function delete(int $id)
    {
    	$of_location = $this->repeater->where('slug', config('chuckcms-module-order-form.locations.slug'))->where('id', $id)->first();
        if (is_null($of_location)) {
            return 'false';
        }
        
        if ($of_location->delete()) {
            return 'success';
        }

        return 'error';    
    }

}
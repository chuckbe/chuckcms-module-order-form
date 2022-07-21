<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;

class CategoryRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    public function find($id)
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.categories.slug'))
            ->find($id);
    }

    /**
     * Get all the categories
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.categories.slug'))->get();
    }

    public function save(Request $values)
    {
    	$input = [];

    	$input['slug'] = config('chuckcms-module-order-form.categories.slug');
        $input['url'] = config('chuckcms-module-order-form.categories.url').str_slug($values->get('name'), '-');
        $input['page'] = config('chuckcms-module-order-form.categories.page');

    	$json = [];
        $json['name'] = $values->get('name');
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_pos_available'] = ($values->get('is_pos_available') == '1' ? true : false);
        $json['order'] = (int)$values->get('order');

        $input['json'] = $json;

        $of_category = $this->repeater->create($input);

        return $of_category;
    }

    public function update(Request $values)
    {
        $of_category = $this->repeater->where('id', $values->get('id'))->firstOrFail();
        $of_category->url = config('chuckcms-module-order-form.categories.url').str_slug($values->get('name'), '-');

        $json = [];
        $json['name'] = $values->get('name');
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_pos_available'] = ($values->get('is_pos_available') == '1' ? true : false);
        $json['order'] = (int)$values->get('order');

        $of_category->json = $json;

        $of_category->update();

        return $of_category;
    }

    public function delete(int $id)
    {
    	$of_category = $this->repeater->where('slug', config('chuckcms-module-order-form.categories.slug'))->where('id', $id)->first();
        if (is_null($of_category)) {
            return 'false';
        }
        
        if ($of_category->delete()) {
            return 'success';
        }

        return 'error';    
    }

}
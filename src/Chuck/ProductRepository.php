<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use ChuckSite;
use Illuminate\Http\Request;

class ProductRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the products
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))->get();
    }

    public function save(Request $values)
    {
    	$input = [];

    	$input['slug'] = config('chuckcms-module-order-form.products.slug');
        $input['url'] = config('chuckcms-module-order-form.products.url').str_slug(array_values($values->get('name'))[0], '-');
        $input['page'] = config('chuckcms-module-order-form.products.page');

    	$json = [];

        foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['name'][$langKey] = $values->get('name')[$langKey];
            $json['description'][$langKey] = $values->get('description')[$langKey];
        }

        $json['category'] = $values->get('category');
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);

        $json['price']['final'] = $values->get('price')['final'];//verkoopprijs incl btw
        $json['price']['discount'] = $values->get('price')['discount'];//kortingsprijs incl btw

        $json['featured_image'] = $values->get('featured_image');

        $attributes = [];
        if($values->get('attribute_name')[0] !== '' && $values->get('attribute_name')[0] !== null) {
            foreach ($values->get('attribute_name') as $key => $attributeName) {
                $attributes[$key]['name'] = $values->get('attribute_name')[$key];
                $attributes[$key]['price'] = $values->get('attribute_price')[$key];
                $attributes[$key]['image'] = $values->get('attribute_image')[$key];
            }
        }
        $json['attributes'] = $attributes;

        $options = [];
        if($values->get('option_name')[0] !== '' && $values->get('option_name')[0] !== null) {
            foreach ($values->get('option_name') as $key => $optionName) {
                $options[$key]['name'] = $values->get('option_name')[$key];
                $options[$key]['type'] = $values->get('option_type')[$key];
                $options[$key]['values'] = $values->get('option_values')[$key];
            }
        }
        $json['options'] = $options;

        $extras = [];
        if($values->get('extra_name')[0] !== '' && $values->get('extra_name')[0] !== null) {
            foreach ($values->get('extra_name') as $key => $extraName) {
                $extras[$key]['name'] = $values->get('extra_name')[$key];
                $extras[$key]['price'] = $values->get('extra_price')[$key];
            }
        }
        $json['extras'] = $extras;

        $input['json'] = $json;

        $product = $this->repeater->create($input);

        return $product;
    }

    public function update(Request $values)
    {
        $of_product = $this->repeater->where('id', $values->get('product_id'))->firstOrFail();

        $of_product->url = config('chuckcms-module-order-form.products.url').str_slug(array_values($values->get('name'))[0], '-');

        $json = [];

        foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['name'][$langKey] = $values->get('name')[$langKey];
            $json['description'][$langKey] = $values->get('description')[$langKey];
        }

        $json['category'] = $values->get('category');
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);

        $json['price']['final'] = $values->get('price')['final'];//verkoopprijs incl btw
        $json['price']['discount'] = $values->get('price')['discount'];//kortingsprijs incl btw

        $json['featured_image'] = $values->get('featured_image');

        $attributes = [];
        if($values->get('attribute_name')[0] !== '' && $values->get('attribute_name')[0] !== null) {
            foreach ($values->get('attribute_name') as $key => $attributeName) {
                $attributes[$key]['name'] = $values->get('attribute_name')[$key];
                $attributes[$key]['price'] = $values->get('attribute_price')[$key];
                $attributes[$key]['image'] = $values->get('attribute_image')[$key];
            }
        }
        $json['attributes'] = $attributes;

        $options = [];
        if($values->get('option_name')[0] !== '' && $values->get('option_name')[0] !== null) {
            foreach ($values->get('option_name') as $key => $optionName) {
                $options[$key]['name'] = $values->get('option_name')[$key];
                $options[$key]['type'] = $values->get('option_type')[$key];
                $options[$key]['values'] = $values->get('option_values')[$key];
            }
        }
        $json['options'] = $options;

        $extras = [];
        if($values->get('extra_name')[0] !== '' && $values->get('extra_name')[0] !== null) {
            foreach ($values->get('extra_name') as $key => $optionName) {
                $extras[$key]['name'] = $values->get('extra_name')[$key];
                $extras[$key]['price'] = $values->get('extra_price')[$key];
            }
        }
        $json['extras'] = $extras;

        $of_product->json = $json;

        $of_product->update();

        return $of_product;
    }

    public function delete(int $id)
    {
    	$product = $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))->where('id', $id)->first();
        if ($product) {
            if ($product->delete()) {
                return 'success';
            }
            return 'error';    
        }
        return 'false';
    }

}
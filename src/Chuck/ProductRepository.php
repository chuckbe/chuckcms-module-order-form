<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CategoryRepository;
use Chuckbe\Chuckcms\Models\Repeater;
use ChuckSite;
use ChuckRepeater;
use Illuminate\Http\Request;

class ProductRepository
{
    protected $categoryRepository;

    private $repeater;

    public function __construct(CategoryRepository $categoryRepository, Repeater $repeater)
    {
        $this->categoryRepository = $categoryRepository;
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

    /**
     * Get all the products for a category
     *
     **/
    public function forCategory(Repeater $category, $count = false)
    {
        $q = $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))
            ->where('json->category', $category->id);

        return $count ? $q->count() : $q->get();
    }

    /**
     * Get all the products for given array of ids
     *
     * @var string
     **/
    public function whereIn(array $ids)
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))
            ->whereIn('id', $ids)
            ->get();
    }

    public function save(Request $values)
    {
        $input = [];

        $input['slug'] = config('chuckcms-module-order-form.products.slug');
        $input['url'] = config('chuckcms-module-order-form.products.url') . str_slug(array_values($values->get('name'))[0], '-');
        $input['page'] = config('chuckcms-module-order-form.products.page');

        $json = [];

        foreach (ChuckSite::getSupportedLocales() as $langKey => $langValue) {
            $json['name'][$langKey] = $values->get('name')[$langKey];
            $json['description'][$langKey] = $values->get('description')[$langKey];
        }

        $category = $this->categoryRepository->find($values->get('category'));

        $json['category'] = $category->id;
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);
        $json['is_pos_available'] = ($values->get('is_pos_available') == '1' ? true : false);

        $json['price']['final'] = $values->get('price')['final'];//verkoopprijs incl btw
        $json['price']['discount'] = is_null($values->get('price')['discount']) ? '0.000000' : $values->get('price')['discount'];//kortingsprijs incl btw
        $json['price']['vat_delivery'] = $values->get('price')['vat_delivery'];
        $json['price']['vat_takeout'] = $values->get('price')['vat_takeout'];
        $json['price']['vat_on_the_spot'] = $values->get('price')['vat_on_the_spot'];

        $json['dates_enabled'] = is_null($values->get('dates_enabled')) ? [] : explode(',', $values->get('dates_enabled'));

        $quantity = [];
        foreach (ChuckRepeater::for(config('chuckcms-module-order-form.locations.slug')) as $location) {
            $quantity[$location->id] = $values->get('quantity')['' . $location->id . ''];
        }
        $json['quantity'] = $quantity;

        $json['featured_image'] = $values->get('featured_image');

        $attributes = [];
        if ($values->get('attribute_name')[0] !== '' && $values->get('attribute_name')[0] !== null) {
            foreach ($values->get('attribute_name') as $key => $attributeName) {
                $attributes[$key]['name'] = $values->get('attribute_name')[$key];
                $attributes[$key]['price'] = $values->get('attribute_price')[$key];
                $attributes[$key]['image'] = $values->get('attribute_image')[$key];
            }
        }
        $json['attributes'] = $attributes;

        $options = [];
        if ($values->get('option_name')[0] !== '' && $values->get('option_name')[0] !== null) {
            foreach ($values->get('option_name') as $key => $optionName) {
                $options[$key]['name'] = $values->get('option_name')[$key];
                $options[$key]['type'] = $values->get('option_type')[$key];
                $options[$key]['values'] = $values->get('option_values')[$key];
            }
        }
        $json['options'] = $options;

        $extras = [];
        if ($values->get('extra_name')[0] !== '' && $values->get('extra_name')[0] !== null) {
            foreach ($values->get('extra_name') as $key => $extraName) {
                $extras[$key]['name'] = $values->get('extra_name')[$key];
                $extras[$key]['price'] = $values->get('extra_price')[$key];
                $extras[$key]['vat_delivery'] = $values->get('extra_vat_delivery')[$key];
                $extras[$key]['vat_takeout'] = $values->get('extra_vat_takeout')[$key];
                $extras[$key]['vat_on_the_spot'] = $values->get('extra_vat_on_the_spot')[$key];
            }
        }
        $json['extras'] = $extras;


        $subproducts = [];

        foreach ($values->get('subproducts') as $key => $subproductGroup) {
            if ($values->get('subproducts')[$key]['name'] !== null && $values->get('subproducts')[$key]['label'] !== null) {
                $products = [];

                foreach ($subproductGroup['products'] as $product) {
                    $products[] = $product;
                }

                $subproducts[]  = array(
                    'name'      => $values->get('subproducts')[$key]['name'],
                    'label'     => $values->get('subproducts')[$key]['label'],
                    'min'       => $values->get('subproducts')[$key]['min'],
                    'max'       => $values->get('subproducts')[$key]['max'],
                    'products'  => $products
                );
            }
        }

        $json['subproducts'] = $subproducts;
        $json['order'] = (int)$this->forCategory($category, true) + 1;

        $input['json'] = $json;

        $product = $this->repeater->create($input);

        return $product;
    }

    public function update(Request $values)
    {
        $of_product = $this->repeater->where('id', $values->get('product_id'))->firstOrFail();

        $of_product->url = config('chuckcms-module-order-form.products.url') . str_slug(array_values($values->get('name'))[0], '-');

        $json = [];

        foreach (ChuckSite::getSupportedLocales() as $langKey => $langValue) {
            $json['name'][$langKey] = $values->get('name')[$langKey];
            $json['description'][$langKey] = $values->get('description')[$langKey];
        }

        $json['category'] = $values->get('category');
        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);
        $json['is_pos_available'] = ($values->get('is_pos_available') == '1' ? true : false);

        $json['price']['final'] = $values->get('price')['final'];//verkoopprijs incl btw
        $json['price']['discount'] = is_null($values->get('price')['discount']) ? '0.000000' : $values->get('price')['discount'];//kortingsprijs incl btw
        $json['price']['vat_delivery'] = $values->get('price')['vat_delivery'];
        $json['price']['vat_takeout'] = $values->get('price')['vat_takeout'];
        $json['price']['vat_on_the_spot'] = $values->get('price')['vat_on_the_spot'];

        $json['dates_enabled'] = is_null($values->get('dates_enabled')) ? [] : explode(',', $values->get('dates_enabled'));

        $quantity = [];
        foreach (ChuckRepeater::for(config('chuckcms-module-order-form.locations.slug')) as $location) {
            $quantity[$location->id] = $values->get('quantity')['' . $location->id . ''];
        }
        $json['quantity'] = $quantity;

        $json['featured_image'] = $values->get('featured_image');

        $attributes = [];
        if ($values->get('attribute_name')[0] !== '' && $values->get('attribute_name')[0] !== null) {
            foreach ($values->get('attribute_name') as $key => $attributeName) {
                $attributes[$key]['name'] = $values->get('attribute_name')[$key];
                $attributes[$key]['price'] = $values->get('attribute_price')[$key];
                $attributes[$key]['image'] = $values->get('attribute_image')[$key];
            }
        }
        $json['attributes'] = $attributes;

        $options = [];
        if ($values->get('option_name')[0] !== '' && $values->get('option_name')[0] !== null) {
            foreach ($values->get('option_name') as $key => $optionName) {
                $options[$key]['name'] = $values->get('option_name')[$key];
                $options[$key]['type'] = $values->get('option_type')[$key];
                $options[$key]['values'] = $values->get('option_values')[$key];
            }
        }
        $json['options'] = $options;

        $extras = [];
        if ($values->get('extra_name')[0] !== '' && $values->get('extra_name')[0] !== null) {
            foreach ($values->get('extra_name') as $key => $optionName) {
                $extras[$key]['name'] = $values->get('extra_name')[$key];
                $extras[$key]['price'] = $values->get('extra_price')[$key];
                $extras[$key]['vat_delivery'] = $values->get('extra_vat_delivery')[$key];
                $extras[$key]['vat_takeout'] = $values->get('extra_vat_takeout')[$key];
                $extras[$key]['vat_on_the_spot'] = $values->get('extra_vat_on_the_spot')[$key];
            }
        }
        $json['extras'] = $extras;

        $subproducts = [];

        foreach ($values->get('subproducts') as $key => $subproductGroup) {
            if ($values->get('subproducts')[$key]['name'] !== null && $values->get('subproducts')[$key]['label'] !== null) {

                $products = [];

                foreach ($subproductGroup['products'] as $product) {
                    $products[] = $product;
                }

                $subproducts[]  = array(
                    'name'      => $values->get('subproducts')[$key]['name'],
                    'label'     => $values->get('subproducts')[$key]['label'],
                    'min'       => $values->get('subproducts')[$key]['min'],
                    'max'       => $values->get('subproducts')[$key]['max'],
                    'products'  => $products
                );
            }
        }

        $json['subproducts'] = $subproducts;

        $of_product->json = $json;

        $of_product->update();

        return $of_product;
    }

    public function find($id)
    {
        $product = $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))->where('id', $id)->first();
        if ($product) {
            return $product;
        }
        return 'false';
    }

    public function sortForCategory(Repeater $category, array $sort)
    {
        $products = $this->forCategory($category);

        for ($q=0; $q < count($sort); $q++) { 
            $product = $products->where('id', $sort[$q])->first();
            $json = $product->json;
            $json['order'] = (int)($q + 1);
            $product->json = $json;
            $product->update();
        }
    }

    public function getProductsAvailableForSub()
    {

        $productsAvailableForSub = $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))->get()->filter(function($i) {
            return $i->json['extras'] == [] && $i->json['options'] == [] && $i->json['attributes'] == [] && (!isset($i->json['subproducts']) || $i->json['subproducts'] == []);
        });
        return $productsAvailableForSub;
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

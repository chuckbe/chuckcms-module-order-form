<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;

use Chuckbe\Chuckcms\Models\Repeater;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    private $productRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->get();
        return view('chuckcms-module-order-form::backend.products.index', compact('products'));
    }

    public function create()
    {
        $subproducts = $this->productRepository->get()->filter(function($i) {
            return $i->json['extras'] == [] && $i->json['options'] == [] && $i->json['attributes'] == [];
        });
        return view('chuckcms-module-order-form::backend.products.create', compact('subproducts'));
    }

    public function edit(Repeater $product)
    {
        $subproducts = $this->productRepository->get()->filter(function($i) {
            return $i->json['extras'] == [] && $i->json['options'] == [] && $i->json['attributes'] == [];
        });
        return view('chuckcms-module-order-form::backend.products.edit', compact('product','subproducts'));
    }

    public function save(Request $request)
    {
        $this->validate(request(), [ //@todo create custom Request class for product validation
            'name.*' => 'required',
            'category' => 'required',
            'is_displayed' => 'required',
            'is_buyable' => 'required',
            'is_pos_available' => 'required',
            'price.final' => 'required',
            'quantity.*' => 'required'
        ]);

        $subproducts = $request->get('subproducts');

        foreach ($subproducts as $key => $subproductGroup) {
            if($subproductGroup['name'] !== null && (!isset($subproductGroup['products']) || empty($subproductGroup['products']))){
                return back()->withErrors(['noproducts' => ['add products to subproducts']]);
            }
        }

        $product = $this->productRepository->save($request);

        return redirect()->route('dashboard.module.order_form.products.index');
    }

    public function delete(Request $request)
    {
        $this->validate(request(), [ //@todo create custom Request class for product validation
            'product_id' => 'required'
        ]);

        $status = $this->productRepository->delete($request->get('product_id'));

        return $status;
    }

    public function update(Request $request)
    {
        $this->validate(request(), [ //@todo create custom Request class for product validation
            'product_id' => 'required',
            'name.*' => 'required',
            'category' => 'required',
            'is_displayed' => 'required',
            'is_buyable' => 'required',
            'is_pos_available' => 'required',
            'price.final' => 'required',
            'quantity.*' => 'required'
        ]);

        $subproducts = $request->get('subproducts');

        foreach ($subproducts as $key => $subproductGroup) {
            if($subproductGroup['name'] !== null && (!isset($subproductGroup['products']) || empty($subproductGroup['products']))){
                return back()->withErrors(['noproducts' => ['add products to subproducts']]);
            }
        }

        $product = $this->productRepository->update($request);

        return redirect()->route('dashboard.module.order_form.products.index');
    }
    
}

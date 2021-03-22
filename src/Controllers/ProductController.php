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
        return view('chuckcms-module-order-form::backend.products.create');
    }

    public function edit(Repeater $product)
    {
        return view('chuckcms-module-order-form::backend.products.edit', compact('product'));
    }

    public function save(Request $request)
    {
        $this->validate(request(), [ //@todo create custom Request class for product validation
            'name.*' => 'required',
            'category' => 'required',
            'is_displayed' => 'required',
            'is_buyable' => 'required',
            'price.final' => 'required',
            'quantity.*' => 'required'
        ]);

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
            'price.final' => 'required',
            'quantity.*' => 'required'
        ]);

        $product = $this->productRepository->update($request);

        return redirect()->route('dashboard.module.order_form.products.index');
    }
    
}

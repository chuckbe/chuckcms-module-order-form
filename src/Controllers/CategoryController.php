<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CategoryRepository;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Repeater as Category;

class CategoryController extends Controller
{
    private $productRepository;
    private $categoryRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository, 
        CategoryRepository $categoryRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->get();
        return view('chuckcms-module-order-form::backend.categories.index', compact('categories'));
    }

    public function sorting(Category $category)
    {
        $products = $this->productRepository->forCategory($category);

        return view('chuckcms-module-order-form::backend.categories.sorting', compact('category', 'products'));
    }

    public function updateSort(Request $request, Category $category)
    {
        $this->validate($request, [ 
            'products' => 'required|array'
        ]);

        $this->productRepository->sortForCategory($category, $request->products);

        return response()->json(['status' => 'success']);
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'is_displayed' => 'required|in:0,1',
            'is_pos_available' => 'required|in:0,1',
            'order' => 'numeric|required',
            'id' => 'required_with:update'
        ]);
        
        if($request->has('create')) {
            $category = $this->categoryRepository->save($request);
        }

        if($request->has('id') && $request->has('update')) {
            $category = $this->categoryRepository->update($request);
        } 

        if(!$category->save()){
            return 'error';//add ThrowNewException
        }

        return redirect()->route('dashboard.module.order_form.categories.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->categoryRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.order_form.categories.index');
        }
    }

    
}

<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CategoryRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    private $categoryRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->get();
        return view('chuckcms-module-order-form::backend.categories.index', compact('categories'));
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

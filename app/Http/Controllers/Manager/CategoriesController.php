<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Forum\Category\Services\CategoryService;
use App\Http\Requests\Category\CategoryRequest;

class CategoriesController extends Controller
{
    private $currentPage;
    private $categoryRepository;
    private $categoryService;

    public function __construct (
        CategoryRepositoryInterface $categoryRepository,
        CategoryService $categoryService
    )
	{
        $this->currentPage = 'categories';
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
	}

    public function index()
    {
        $currentPage = $this->currentPage;
        $categories = $this->categoryRepository->all();

        return view('manager.categories.index')->with(compact(
            'currentPage',
            'categories'
        ));
    }

    public function create()
    {
        $currentPage = $this->currentPage;
        $edit        = false;
        $category    = $this->categoryRepository;

        return view('manager.categories.form')->with(compact(
            'currentPage',
            'edit',
            'category'
        ));
    }

    public function store(CategoryRequest $request)
    {
        $request = $this->categoryService->store($request->except('_token', '_method'));

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.categories.index');
    }

    public function destroy($id)
    {
        $request = $this->categoryService->destroy($id);

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.categories.index');
    }
}

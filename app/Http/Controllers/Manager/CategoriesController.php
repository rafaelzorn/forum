<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;

class CategoriesController extends Controller
{
    private $currentPage;
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
	{
        $this->currentPage = 'categories';
        $this->categoryRepository = $categoryRepository;
	}

    public function index()
    {
        return view('manager.categories.index');
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
}

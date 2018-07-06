<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('manager.categories.index');
    }
}

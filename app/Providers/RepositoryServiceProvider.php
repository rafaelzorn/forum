<?php

namespace App\Providers;

use App\Forum\Category\Repositories\CategoryRepository;
use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    }
}

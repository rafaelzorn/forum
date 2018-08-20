<?php

namespace App\Providers;

use App\Forum\Category\Repositories\CategoryRepository;
use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Forum\Topic\Repositories\TopicRepository;
use App\Forum\Topic\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            TopicRepositoryInterface::class,
            TopicRepository::class
        );
    }
}

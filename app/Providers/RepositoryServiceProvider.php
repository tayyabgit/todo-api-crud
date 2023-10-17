<?php

namespace App\Providers;

use App\Repositories\TodoRepository;
use App\Repositories\TodoRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Iterator\CustomFilterIterator;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(TodoRepositoryInterface::class, TodoRepository::class);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\FileTaskRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            TaskRepositoryInterface::class, 
            FileTaskRepository::class
        );
    }

    public function boot()
    {
        //s
    }
}

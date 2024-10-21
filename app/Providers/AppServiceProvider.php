<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProvinceRepositoryInterface;
    use App\Repositories\ProvinceRepository;
class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
    }

    public function boot(): void
    {
        //
    }
}

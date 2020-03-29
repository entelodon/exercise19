<?php

namespace App\Providers;

use App\Company\Services\DataRetrieveService;
use Illuminate\Support\ServiceProvider;

class DataRetrieveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DataRetrieveService::class, function ($app) {
            return new DataRetrieveService(env('DATASERVER_ENDPOINT'), env('DATASERVER_METHOD'), env('DATASERVER_STRUCTURE'));
        });
    }
}

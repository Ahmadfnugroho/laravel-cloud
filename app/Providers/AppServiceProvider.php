<?php

namespace App\Providers;

use App\Models\DetailTransaction;
use App\Observers\DetailTransactionObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    public function boot()
    {
        DetailTransaction::observe(DetailTransactionObserver::class);
    }
    /**
     * Bootstrap any application services.
     */
}

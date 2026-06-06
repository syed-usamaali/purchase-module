<?php

namespace App\Providers;

use App\Models\Purchase;
use App\Policies\PurchasePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Purchase::class, PurchasePolicy::class);
        Livewire::component('purchase-manager', \App\Http\Livewire\PurchaseManager::class);
        Livewire::component('purchase-list', \App\Http\Livewire\PurchaseList::class);
    }
}

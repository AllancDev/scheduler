<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Register layout components
        Blade::component('app-layout', \App\View\Components\AppLayout::class);
        
        // Register form components
        Blade::component('input-label', \App\View\Components\InputLabel::class);
        Blade::component('text-input', \App\View\Components\TextInput::class);
        Blade::component('input-error', \App\View\Components\InputError::class);
        
        // Register button components
        Blade::component('primary-button', \App\View\Components\PrimaryButton::class);
        Blade::component('secondary-button', \App\View\Components\SecondaryButton::class);
        Blade::component('danger-button', \App\View\Components\DangerButton::class);
        
        // Register navigation components
        Blade::component('nav-link', \App\View\Components\NavLink::class);
        Blade::component('responsive-nav-link', \App\View\Components\ResponsiveNavLink::class);
        Blade::component('dropdown', \App\View\Components\Dropdown::class);
        Blade::component('dropdown-link', \App\View\Components\DropdownLink::class);
    }
}

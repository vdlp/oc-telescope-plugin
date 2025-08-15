<?php

declare(strict_types=1);

namespace Vdlp\Telescope\ServiceProviders;

use Backend\Classes\AuthManager;
use Backend\Models\BrandSetting;
use Backend\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider as TelescopeServiceProviderBase;
use Vdlp\Telescope\Controllers\HomeController;

final class TelescopeServiceProvider extends TelescopeServiceProviderBase
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerPublishing();

        if (config('telescope.enabled') === false) {
            return;
        }

        $this->authorization();

        Route::middlewareGroup('telescope', config('telescope.middleware', []));

        $this->registerRoutes();
        $this->registerResources();

        if (BrandSetting::getColorMode() === BrandSetting::COLOR_DARK) {
            Telescope::night();
        }

        Telescope::start($this->app);
        Telescope::listenForStorageOpportunities($this->app);

        $this->loadViewsFrom(plugins_path('vdlp/telescope/views'), 'telescope');
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function (): void {
            $this->loadRoutesFrom(base_path('vendor/laravel/telescope/routes/web.php'));

            // Override HomeController@index
            Route::get('/{view?}', [HomeController::class, 'index'])
                ->where('view', '(.*)')
                ->name('telescope');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     */
    private function routeConfiguration(): array
    {
        return [
            'domain' => config('telescope.domain'),
            'namespace' => 'Laravel\Telescope\Http\Controllers',
            'prefix' => config('telescope.path'),
            'middleware' => 'telescope',
        ];
    }

    /**
     * Register the Telescope resources.
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(base_path('vendor/laravel/telescope/resources/views'), 'telescope');
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $publishesMigrationsMethod = method_exists($this, 'publishesMigrations')
            ? 'publishesMigrations'
            : 'publishes';

        $this->{$publishesMigrationsMethod}([
            base_path('vendor/laravel/telescope/database/migrations') => database_path('migrations'),
        ], 'telescope-migrations');

        $this->publishes([
            base_path('vendor/laravel/telescope/config/telescope.php') => config_path('telescope.php'),
        ], 'telescope-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(base_path('vendor/laravel/telescope/config/telescope.php'), 'telescope');

        $this->registerStorageDriver();
    }

    /**
     * Configure the Telescope authorization services.
     */
    private function authorization(): void
    {
        Telescope::auth(static function (): bool {
            /** @var ?User $user */
            $user = AuthManager::instance()->getUser();

            if ($user === null) {
                return false;
            }

            return $user->hasPermission('vdlp.telescope.access_dashboard')
                || $user->isSuperUser();
        });
    }
}

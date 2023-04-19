<?php

declare(strict_types=1);

namespace Vdlp\Telescope\ServiceProviders;

use ApplicationException;
use Backend\Classes\AuthManager;
use Backend\Models\User;
use Cms\Classes\Theme;
use Illuminate\Support\Facades\Route;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider as TelescopeServiceProviderBase;

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
        $this->registerMigrations();

        Telescope::night();
        Telescope::start($this->app);
        Telescope::listenForStorageOpportunities($this->app);

        $this->loadViewsFrom(plugins_path('vdlp/telescope/views'), 'telescope');
    }

    /**
     * Register the package routes.
     */
    private function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function (): void {
            $this->loadRoutesFrom(base_path('vendor/laravel/telescope/src/Http/routes.php'));

            // Override HomeController@index
            Route::get('/{view?}', '\Vdlp\Telescope\Controllers\HomeController@index')
                ->where('view', '(.*)')
                ->name('telescope');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
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
     * Register the package's migrations.
     */
    private function registerMigrations(): void
    {
        if ($this->app->runningInConsole() && $this->shouldMigrate()) {
            $this->loadMigrationsFrom(base_path('vendor/laravel/telescope/src/Storage/migrations'));
        }
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            base_path('vendor/laravel/telescope/public') => $this->getAssetPath(),
        ], 'telescope-assets');

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
     * Get the asset path for the current active October CMS theme.
     *
     * @throws ApplicationException
     */
    private function getAssetPath(): string
    {
        /** @var ?Theme $theme */
        $theme = Theme::getActiveTheme();

        if ($theme === null) {
            return '';
        }

        return $theme->getPath(implode(DIRECTORY_SEPARATOR, [
            $theme->getDirName(),
            'assets',
            'telescope',
        ]));
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

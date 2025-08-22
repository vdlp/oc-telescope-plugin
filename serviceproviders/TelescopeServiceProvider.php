<?php

declare(strict_types=1);

namespace Vdlp\Telescope\ServiceProviders;

use Backend\Classes\AuthManager;
use Backend\Models\BrandSetting;
use Backend\Models\User;
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
        $this->registerResources();

        if (BrandSetting::getColorMode() === BrandSetting::COLOR_DARK) {
            Telescope::night();
        }

        Telescope::start($this->app);
        Telescope::listenForStorageOpportunities($this->app);

        $this->loadViewsFrom(plugins_path('vdlp/telescope/views'), 'telescope');
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

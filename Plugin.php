<?php

declare(strict_types=1);

namespace Vdlp\Telescope;

use Backend\Helpers\Backend;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Application;
use System\Classes\PluginBase;
use Vdlp\Telescope\ServiceProviders\TelescopeServiceProvider;

final class Plugin extends PluginBase
{
    private Backend $backend;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->backend = $app->make(Backend::class);
    }

    public function pluginDetails(): array
    {
        return [
            'name' => 'Telescope',
            'description' => 'Laravel Telescope integration for October CMS',
            'author' => 'Van der Let & Partners',
            'icon' => 'icon-area-chart',
            'homepage' => 'https://octobercms.com/plugin/vdlp-telescope',
        ];
    }

    public function register(): void
    {
        if ($this->app->environment('local') === false) {
            return;
        }

        $this->registerAccessGate();

        $this->app->register(TelescopeServiceProvider::class);
    }

    public function registerPermissions(): array
    {
        return [
            'vdlp.telescope.access_dashboard' => [
                'tab' => 'Telescope',
                'label' => 'Access to the Telescope dashboard',
                'roles' => ['developer'],
            ],
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'dashboard' => [
                'label' => 'Telescope',
                'url' => $this->backend->url('vdlp/telescope/dashboard'),
                'iconSvg' => '/plugins/vdlp/telescope/assets/icons/telescope.svg',
                'permissions' => ['vdlp.telescope.access_dashboard'],
                'order' => 510,
            ],
        ];
    }

    /**
     * Register the access gate service.
     */
    private function registerAccessGate(): void
    {
        $this->app->singleton(GateContract::class, static function ($app): GateContract {
            return new Gate($app, static function () use ($app) {
                return call_user_func($app['auth']->userResolver());
            });
        });
    }
}

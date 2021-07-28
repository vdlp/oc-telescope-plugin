<?php

declare(strict_types=1);

namespace Vdlp\Telescope\Controllers;

use Backend\Classes\Controller;
use Backend\Classes\NavigationManager;

final class Dashboard extends Controller
{
    public $requiredPermissions = ['vdlp.telescope.access_dashboard'];
    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.Telescope', 'dashboard');
    }

    public function index(): void
    {
        $this->pageTitle = 'Laravel Telescope';
    }
}

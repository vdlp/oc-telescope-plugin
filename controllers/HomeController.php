<?php

declare(strict_types=1);

namespace Vdlp\Telescope\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Laravel\Telescope\Telescope;
use RuntimeException;
use Vdlp\Telescope\Classes\PathHelper;

final class HomeController extends Controller
{
    /**
     * @throws RuntimeException
     */
    public function index()
    {
        return view('vdlp.telescope::layout', [
            'cssFile' => Telescope::$useDarkTheme ? 'app-dark.css' : 'app.css',
            'telescopeScriptVariables' => Telescope::scriptVariables(),
            'assetsAreCurrent' => $this->assetsAreCurrent(),
        ]);
    }

    private function assetsAreCurrent(): bool
    {
        $pathHelper = new PathHelper();

        $publishedPath = $pathHelper->getAssetsPath('mix-manifest.json');

        if (!File::exists($publishedPath)) {
            throw new RuntimeException('The Telescope assets are not published. Please run: php artisan telescope:publish');
        }

        $vendorPath = base_path('vendor/laravel/telescope/public/mix-manifest.json');

        return File::get($publishedPath) === File::get($vendorPath);
    }
}

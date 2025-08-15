<?php

declare(strict_types=1);

namespace Vdlp\Telescope\Controllers;

use Backend\Models\BrandSetting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Laravel\Telescope\Telescope;
use RuntimeException;

final class HomeController extends Controller
{
    /**
     * @throws RuntimeException
     */
    public function index()
    {
        return view('vdlp.telescope::layout', [
            'cssFile' => BrandSetting::getColorMode() === BrandSetting::COLOR_DARK ? 'app-dark.css' : 'app.css',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Vdlp\Telescope\Console;

use Laravel\Telescope\Console\InstallCommand as TelescopeInstallCommand;

final class InstallCommand extends TelescopeInstallCommand
{
    public function handle(): void
    {
        $this->comment('Publishing Telescope Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-migrations']);

        $this->comment('Publishing Telescope Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-config']);

        $this->info('Telescope scaffolding installed successfully.');
    }
}

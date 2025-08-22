<?php

declare(strict_types=1);

namespace Vdlp\Telescope\Console;

use Illuminate\Console\Command;

final class InstallCommand extends Command
{
    public function __construct()
    {
        $this->signature = 'telescope:install';
        $this->description = 'Install all of the Telescope resources';

        parent::__construct();
    }

    public function handle(): void
    {
        $this->comment('Publishing Telescope migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-migrations']);

        $this->comment('Publishing Telescope Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-config']);

        $this->info('Telescope scaffolding installed successfully.');
    }
}

<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Ngfw\LaravelStackInstaller\Installers\ApiOnlyStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:api-only-stack')]
class InstallApiOnlyStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel API-Only Stack';
    protected string $installerClass = ApiOnlyStackInstaller::class;

    protected function configure()
    {
        $this->setDescription('Installs the Laravel API-Only stack (no frontend integration, just an API backend)');
    }
}

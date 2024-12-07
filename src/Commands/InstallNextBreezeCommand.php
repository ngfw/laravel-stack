<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Ngfw\LaravelStackInstaller\Installers\NextBreezeInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:next-breeze')]
class InstallNextBreezeCommand extends BaseInstallCommand
{
    protected string $title = 'Next.js Breeze Stack';
    protected string $installerClass = NextBreezeInstaller::class;

    protected function configure()
    {
        $this->setDescription('Installs the Laravel + Next.js Breeze stack (frontend and backend integration)');
    }
}

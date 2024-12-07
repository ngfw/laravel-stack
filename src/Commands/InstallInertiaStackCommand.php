<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Ngfw\LaravelStackInstaller\Installers\InertiaStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:inertia-stack')]
class InstallInertiaStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + Inertia.js + Vue.js Stack';
    protected string $installerClass = InertiaStackInstaller::class;

    protected function configure()
    {
        $this->setDescription('Installs the Laravel + Inertia.js + Vue.js stack (for single-page applications)');
    }
}

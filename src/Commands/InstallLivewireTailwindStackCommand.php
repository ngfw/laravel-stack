<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Ngfw\LaravelStackInstaller\Installers\LivewireTailwindStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:livewire-tailwind')]
class InstallLivewireTailwindStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + Livewire + Tailwind CSS Stack';
    protected string $installerClass = LivewireTailwindStackInstaller::class;

    protected function configure()
    {
        $this->setDescription('Installs the Laravel + Livewire + Tailwind CSS stack (for dynamic interfaces)');
    }
}

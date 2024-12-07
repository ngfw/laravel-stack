<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Ngfw\LaravelStackInstaller\Installers\ReactTailwindStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:react-tailwind')]
class InstallReactTailwindStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + React + Tailwind CSS Stack';
    protected string $installerClass = ReactTailwindStackInstaller::class;

    protected function configure()
    {
        $this->setDescription('Installs the Laravel + React + Tailwind CSS stack (for React-based frontends)');
    }
}

<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Ngfw\LaravelStackInstaller\Installers\VueTailwindStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:vue-tailwind')]
class InstallVueTailwindStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + Vue.js + Tailwind CSS Stack';
    protected string $installerClass = VueTailwindStackInstaller::class;

    protected function configure()
    {
        $this->setDescription('Installs the Laravel + Vue.js + Tailwind CSS stack (for Vue.js-based frontends)');
    }
}

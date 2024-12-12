<?php

namespace Ngfw\LaravelStack\Commands;

use Ngfw\LaravelStack\Installers\VITStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:vue-tailwind')]
class InstallVITStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + Vue.js + Tailwind CSS Stack';
    protected string $installerClass = VITStackInstaller::class;

}

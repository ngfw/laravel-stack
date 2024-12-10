<?php

namespace Ngfw\LaravelStack\Commands;

use Ngfw\LaravelStack\Installers\ReactTailwindStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:react-tailwind')]
class InstallReactTailwindStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + React + Tailwind CSS Stack';
    protected string $installerClass = ReactTailwindStackInstaller::class;

}

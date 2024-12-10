<?php

namespace Ngfw\LaravelStack\Commands;

use Ngfw\LaravelStack\Installers\NextBreezeInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:next-breeze')]
class InstallNextBreezeCommand extends BaseInstallCommand
{
    protected string $title = 'Next.js Breeze Stack';
    protected string $installerClass = NextBreezeInstaller::class;
    protected ?string $backendSubDirectory = "api";
    protected ?string $frontendSubDirectory = "www";

}

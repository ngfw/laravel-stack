<?php

namespace Ngfw\LaravelStack\Commands;

use Ngfw\LaravelStack\Installers\ApiOnlyStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:api-only')]
class InstallApiOnlyStackCommand extends BaseInstallCommand
{
    protected string $title = 'API-Only';
    protected string $installerClass = ApiOnlyStackInstaller::class;
    protected ?string $backendSubDirectory = "api";

}

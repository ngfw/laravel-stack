<?php

namespace Ngfw\LaravelStack\Commands;

use Ngfw\LaravelStack\Installers\GraphQLStackInstaller;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'install:graphql-stack')]
class InstallGraphQLStackCommand extends BaseInstallCommand
{
    protected string $title = 'Laravel + GraphQL + Apollo Stack';
    protected string $installerClass = GraphQLStackInstaller::class;

}

<?php 
namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;


class GraphQLStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/graphqlstack.json';
    protected string $boilerplatePath = '/Boilerplates/graphQL/';

    public function setupLighthouse()
    {
        $this->output->writeln("<info>→ Setting up GraphQL...</info>");

        foreach (
            [
                'composer require nuwave/lighthouse' => "nuwave/lighthouse Installed",
                'composer require mll-lab/laravel-graphiql' => "mll-lab/laravel-graphiql installed",
                'php artisan vendor:publish --tag=lighthouse-schema' => "Lighthouse default schema published",
            ] as $command => $message
        ) {
            $this->execute($command, $message);
        }

        $boilerplatePath = realpath(dirname(__FILE__) . "/../") . "{$this->boilerplatePath}";
        #$this->copyDirectory("$boilerplatePath/src", getcwd() . "/{$this->projectName}" ."/".$this->frontendSubDirectory."/src/");
        #$this->copyFile(getcwd() . "/{$this->projectName}" ."/".$this->frontendSubDirectory."/.env.example", getcwd() . "/{$this->projectName}" ."/".$this->frontendSubDirectory."/.env.local");
        
        $this->output->writeln("<info>✓ GraphQL setup is completed.</info>");
        return true;
    }

    public function installGraphQL_NPMPackages()
    {
        return $this->execute("npm install @apollo/client graphql", "@apollo graphql package installed");        
    }
}

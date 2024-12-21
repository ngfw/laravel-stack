<?php 
namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Console\Output\OutputInterface;


class GraphQLStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/graphqlstack.json';
    protected string $boilerplatePath = '/Boilerplates/graphQL/';

    public function __construct($stack, $projectName, $dbHost, $dbUser, $dbPassword, OutputInterface $output, $backendSubDirectory = null, $frontendSubDirectory = null)
    {
        parent::__construct($stack, $projectName, $dbHost, $dbUser, $dbPassword, $output, $backendSubDirectory, $frontendSubDirectory);
        $this->finalNotes[] = "GraphQL stack setup for '{$projectName}' completed successfully!";
        $this->finalNotes[] = "Run Server by executing: ";
        $this->finalNotes[] = "cd {$projectName}/";
        $this->finalNotes[] = "./devServer.sh";
        $this->finalNotes[] = "";
        $this->finalNotes[] = "Visit Application at: http://127.0.0.1:8000/";
        $this->finalNotes[] = "Visit Application at: http://127.0.0.1:8000/graphiql";
        $this->finalNotes[] = "For testing run: { user: user(id: 1) { id name email }} ";
        $this->finalNotes[] = "";
        $this->finalNotes[] = "For Documentation refer to: https://lighthouse-php.com/";
        $this->finalNotes[] = "------------------------";
        $this->finalNotes[] = "Happy Coding!";
    }

    public function setupLighthouse()
    {
        $this->output->writeln("<info>→ Setting up GraphQL...</info>");

        foreach (
            [
                'composer require nuwave/lighthouse' => "nuwave/lighthouse Installed",
                'composer require mll-lab/laravel-graphiql' => "mll-lab/laravel-graphiql installed",
                'php artisan vendor:publish --tag=lighthouse-schema' => "Lighthouse default schema published",
                'npm install -g graphql-cli' => "QraphQL cli installed",
            ] as $command => $message
        ) {
            $this->execute($command, $message);
        }
        $this->runArtisanCommand("tinker --execute=\"\App\Models\User::factory(10)->create();\"");

        $this->output->writeln("<info>✓ GraphQL setup is completed.</info>");
        return true;
    }

    public function setupBoilerplate(){
        $this->output->writeln("<info>→ Setting up Boilerplate...</info>");


        $boilerplatePath = realpath(dirname(__FILE__) . "/../") . "{$this->boilerplatePath}";
        $this->copyDirectory("$boilerplatePath/config", getcwd() . "/{$this->projectName}" ."/config");
        $this->copyDirectory("$boilerplatePath/resources", getcwd() . "/{$this->projectName}" ."/resources");

        $this->output->writeln("<info>✓ Boilerplate installed.</info>");
        return true;
    }

    public function installGraphQL_NPMPackages()
    {
        return $this->execute("npm install @apollo/client graphql", "@apollo graphql package installed");        
    }
}

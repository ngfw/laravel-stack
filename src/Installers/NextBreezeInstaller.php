<?php

namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;

class NextBreezeInstaller extends Installer
{
    protected string $breezeNextURL = "https://github.com/laravel/breeze-next.git";
    protected string $manifestFile = '/Manifests/next.js.json';
    protected string $boilerplatePath = '/Boilerplates/next.js/';

    protected function createEmptyLogFile()
    {
        $logMessageTitle = "Laravel Installation Successfully";
        $logMessage = "Your Next.JS application is running on http://127.0.0.1:3000/ ";
        $logMessage2 = "Your Laravel API is running on http://127.0.0.1:8000/ ";

        $this->output->writeln("<info>→ Running Laravel artisan command: php artisan tinker --execute=\"Log::info('" . $logMessage . "');\"</info>");

        $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessageTitle . "');\"");
        $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessage . "');\"");
        $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessage2 . "');\"");

        return true;
    }

    protected function setupNextFrontend()
    {
        $this->output->writeln("<info>→ Setting up Next.js frontend...</info>");

        $process = Process::fromShellCommandline("git clone {$this->breezeNextURL} {$this->projectName}/www");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to create Next.js project: {$process->getErrorOutput()}</error>");
            return false;
        }

        $boilerplatePath = realpath(dirname(__FILE__) . "/../") . "{$this->boilerplatePath}";
        $this->copyDirectory("$boilerplatePath/src", getcwd() . "/{$this->projectName}" ."/www/src/");
        $this->copyFile(getcwd() . "/{$this->projectName}" ."/www/.env.example", getcwd() . "/{$this->projectName}" ."/www/.env.local");

        $this->output->writeln("<info>✓ Next.js project created.</info>");
        return true;
    }
    protected function setupBreeze()
    {
        $this->output->writeln("<info>→  Setting up Laravel Breeze...</info>");

        $fullPath = $this->projectName . ($this->backendSubDirectory ? "/{$this->backendSubDirectory}" : '');
        $process = Process::fromShellCommandline("cd {$fullPath} && composer require laravel/breeze --dev");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to install laravel/breeze: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Laravel project created.</info>");
        return true;
    }

}

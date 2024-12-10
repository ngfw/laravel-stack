<?php

namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;
use Ngfw\LaravelStack\Helpers\DatabaseHelper;

class TallStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/tall-stack.json';

    public function __construct($stack, $projectName, $dbHost, $dbUser, $dbPassword, OutputInterface $output, $backendSubDirectory = null, $frontendSubDirectory = null)
    {
        parent::__construct($stack, $projectName, $dbHost, $dbUser, $dbPassword, $output, $backendSubDirectory, $frontendSubDirectory);
        $this->finalNotes[] = "TALL stack setup for '{$projectName}' completed successfully!";
        $this->finalNotes[] = "Run Server by executing: ./{$projectName}/dev_server.sh";
        $this->finalNotes[] = "Application homepage: http://127.0.0.1:8000/";
        $this->finalNotes[] = "Admin Panel: http://127.0.0.1:8000/admin/";
        $this->finalNotes[] = "Username: admin@{$projectName}.com";
        $this->finalNotes[] = "Password: admin";
        $this->finalNotes[] = "------------------------";
        $this->finalNotes[] = "Happy Coding!";
    }

    protected function installFilament($step)
    {
        $this->output->writeln("<info>→  Setting up Filament</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && composer require livewire/livewire filament/filament:\"^3.2\" -W");
        $process->run();
        $this->output->writeln("<info>✓ Filament setup completed.</info>");
        return true;
       
    }
    protected function installLaravelPermission($step)
    {
        $this->output->writeln("<info>→  Setting up spatie/laravel-permission</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && composer require spatie/laravel-permission");
        $process->run();
        $this->output->writeln("<info>✓ spatie/laravel-permission setup completed.</info>");
        return true;
       
    }
    
    protected function runMigrations($step)
    {
        $this->output->writeln("<info>→  Running Migration</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan migrate");
        $process->run();
        $this->output->writeln("<info>✓ Filament setup completed.</info>");
        return true;
    }

    protected function installFilamentPanels($step)
    {
        $this->output->writeln("<info>→  Setting up Filament Panels</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan filament:install --panels -n");
        $process->run();
        $this->output->writeln("<info>✓ Filament Panels setup completed.</info>");
        return true;
    }

    protected function optimizeInstallation($step)
    {
        $this->output->writeln("<info>→  Optimizing Installation</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan filament:optimize && php artisan optimize:clear");
        $process->run();
        $this->output->writeln("<info>✓ Optimized! </info>");
        return true;
    }

    protected function createAdminUser($step)
    {
        $this->output->writeln("<info>→  Creating admin user.</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan make:filament-user --name=Admin --email=admin@{$this->projectName}.com --password=admin");
        $process->run();
        $this->output->writeln("<info>✓ Done - email: admin@{$this->projectName}.com password: admin </info>");
        return true;
    }
}

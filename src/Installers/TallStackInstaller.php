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
        return $this->execute(
            "composer require livewire/livewire filament/filament:\"^3.2\" -W",
            "Filament setup completed."
        );
    }
    protected function installLaravelPermission($step)
    {
        return $this->execute(
            "composer require spatie/laravel-permission",
            "spatie/laravel-permission setup completed."
        );
    }

    protected function runMigrations($step)
    {
        return $this->execute(
            "php artisan migrate",
            "Filament setup completed."
        );
    }

    protected function installFilamentPanels($step)
    {
        return $this->execute(
            "php artisan filament:install --panels -n",
            "Filament Panels setup completed."
        );
    }

    protected function optimizeInstallation($step)
    {
        return $this->execute(
            "php artisan filament:optimize && php artisan optimize:clear",
            "Optimized! "
        );
    }

    protected function createAdminUser($step)
    {
        return $this->execute(
            "php artisan make:filament-user --name=Admin --email=admin@{$this->projectName}.com --password=admin",
            "Done - email: admin@{$this->projectName}.com password: admin "
        );
    }
}

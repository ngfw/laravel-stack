<?php 
namespace Ngfw\LaravelStack\Installers;

use Ngfw\LaravelStack\Installers\Installer;


class ApiOnlyStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/apionlystack.json';
    protected ?string $backendSubDirectory = 'api';

    protected function createEmptyLogFile()
    {
        $logMessageTitle = "Laravel Installation Successfully";
        $logMessage = "Your Laravel API is running on http://127.0.0.1:8000/ ";

        $this->output->writeln("<info>→ Running Laravel artisan command: php artisan tinker --execute=\"Log::info('" . $logMessage . "');\"</info>");

        $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessageTitle . "');\"");
        $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessage . "');\"");
        
        return true;
    }
}

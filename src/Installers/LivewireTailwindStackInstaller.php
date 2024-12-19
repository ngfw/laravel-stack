<?php 
namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;
use Ngfw\LaravelStack\Helpers\DatabaseHelper;
use Symfony\Component\Console\Output\OutputInterface;

class LivewireTailwindStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/livewire.json';
    protected string $boilerplatePath = '/Boilerplates/livewire/';

    public function setupLivewire(){
        $this->output->writeln("<info>→ Setting up Livewire...</info>");

        $fullPath = $this->projectName;
        $process = Process::fromShellCommandline("cd {$fullPath} && composer require livewire/livewire && php artisan livewire:publish --config");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to install Livewire: {$process->getErrorOutput()}</error>");
            return false;
        }
        
        $this->output->writeln("<info>✓ Livewire installed.</info>");
        return true;
    }

    public function setupBoilerplate(){
        $this->output->writeln("<info>→ Setting up Boilerplate...</info>");


        $boilerplatePath = realpath(dirname(__FILE__) . "/../") . "{$this->boilerplatePath}";
        $this->copyDirectory("$boilerplatePath/resources", getcwd() . "/{$this->projectName}" ."/resources");

        $this->output->writeln("<info>✓ Boilerplate installed.</info>");
        return true;
    }
}

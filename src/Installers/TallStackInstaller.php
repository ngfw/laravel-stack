<?php

namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;
use Ngfw\LaravelStack\Helpers\DatabaseHelper;

class TallStackInstaller extends Installer
{


    protected $manifestFile = '../Manifests/tall-stack.json';


    protected function renderComplitionBanner(){
        $projectPath = getcwd() . "/{$this->projectName}/";
        $this->output->writeln("<info>TALL stack setup for '{$projectPath}' completed successfully!</info>");
        $this->output->writeln("<info>Run Server by executing: ./{$this->projectName}/dev_server.sh </info>");
        $this->output->writeln("<info>Admin Panel: http://127.0.0.1:8000 </info>");
        $this->output->writeln("<info>Username: admin@{$this->projectName}.com </info>");
        $this->output->writeln("<info>Password: admin </info>");
        $this->output->writeln("<info>------------------------ </info>");
        $this->output->writeln("<info>Happy Coding! </info>");
    }

    protected function handleSetupProject($step)
    {
        $process = Process::fromShellCommandline("composer create-project laravel/laravel {$this->projectName}");
        $process->run();
    }


    protected function handleSetupDatabase($step)
    {
        $dbHelper = new DatabaseHelper($this->dbHost, $this->dbUser, $this->dbPassword);
        if (!$dbHelper->createDatabase($this->projectName)) {
            $this->output->writeln("<error>Database '{$this->projectName}' already exists or cannot be created.</error>");
            return false;
        }
    }

    protected function handleInstallPackages($step)
    {
        $projectPath = getcwd() . "/{$this->projectName}/";
        $process = Process::fromShellCommandline("cd {$projectPath} && composer require livewire/livewire filament/filament:\"^3.2\" -W");
        $process->run();
        $process = Process::fromShellCommandline("cd {$projectPath} && composer require spatie/laravel-permission");
        $process->run();
    }

    
    protected function handleRunMigrations($step)
    {
        $projectPath = getcwd() . "/{$this->projectName}/";
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan migrate");
        $process->run();
    }

    protected function handleInstallFilamentPanels($step)
    {
        $projectPath = getcwd() . "/{$this->projectName}/";
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan filament:install --panels -n");
        $process->run();
    }

    protected function handleInstallNpm($step)
    {
        $projectPath = getcwd() . "/{$this->projectName}/";
        $process = Process::fromShellCommandline("cd {$projectPath} && npm install");
        $process->run();
    }

    protected function handleOptimizeInstallation($step)
    {
        $projectPath = getcwd() . "/{$this->projectName}/";
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan filament:optimize && php artisan optimize:clear");
        $process->run();
    }

    protected function handleCreateAdminUser($step)
    {
        $projectPath = getcwd() . "/{$this->projectName}/";
        $process = Process::fromShellCommandline("cd {$projectPath} && php artisan make:filament-user --name=Admin --email=admin@{$this->projectName}.com --password=admin");
        $process->run();
    }

    protected function handleAddDevServerScript($step)
    {
        $this->addDevServerScript();
    }

    protected function configureEnv()
    {
        $envPath = getcwd() . "/{$this->projectName}/.env";
        if (!file_exists($envPath)) {
            throw new \Exception("The .env file for the project '{$this->projectName}' ($envPath) was not found.");
        }

        $this->replaceInFile($envPath, 'APP_NAME=.*', "APP_NAME=\"{$this->projectName}\"");
        $this->replaceInFile($envPath, 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql');
        $this->replaceInFile($envPath, '# DB_DATABASE=.*', "DB_DATABASE={$this->projectName}");
        $this->replaceInFile($envPath, '# DB_USERNAME=.*', "DB_USERNAME={$this->dbUser}");
        $this->replaceInFile($envPath, '# DB_PASSWORD=.*', "DB_PASSWORD={$this->dbPassword}");
        $this->replaceInFile($envPath, '# DB_HOST=.*', "DB_HOST={$this->dbHost}");
        $this->replaceInFile($envPath, 'APP_URL=.*', "APP_URL=http://127.0.0.1:8000");
    }



    protected function replaceInFile($filePath, $searchPattern, $replacement)
    {
        $fileContents = file_get_contents($filePath);
        $updatedContents = preg_replace("/^{$searchPattern}/m", $replacement, $fileContents);
        file_put_contents($filePath, $updatedContents);
    }

    protected function runShellCommand($command)
    {
        $output = shell_exec($command);
        if ($output === null) {
            throw new \Exception("Command failed: $command");
        }
        $this->output->writeln($output);
    }

    protected function addDevServerScript()
    {
        $this->output->writeln("<info>→  Adding devServer.sh script...</info>");

        $projectDir = getcwd() . '/' . $this->projectName;
        $scriptPath = $projectDir . '/devServer.sh';

        $scriptContent = <<<SCRIPT
#!/bin/bash

################################################################################
#                                                                              #
#                          Laravel Dev Server Script                           #
#                                                                              #
#   This script automates the setup of a development environment using tmux.   #
################################################################################

if ! command -v tmux &> /dev/null; then
    echo "Error: tmux is not installed. Please install tmux and try again."
    exit 1
fi

WWW_DIR=$(cd "\$(dirname "\$0")" && pwd)

SESSION_NAME="dev_server"

LOG_FILE="\$WWW_DIR/storage/logs/laravel.log"

tmux new-session -d -s \$SESSION_NAME

tmux split-window -h -t \$SESSION_NAME       
tmux split-window -v -t \$SESSION_NAME:0.0  
tmux send-keys -t \$SESSION_NAME:0.0 "cd \$WWW_DIR && php artisan serve" Enter
tmux send-keys -t \$SESSION_NAME:0.1 "cd \$WWW_DIR && npm run dev" Enter
tmux send-keys -t \$SESSION_NAME:0.2 "tail -f \$LOG_FILE" Enter

tmux bind-key -n F1 display-message "Laravel Installer Dev Server 1.0.1"
tmux bind-key -n F10 confirm-before -p "Are you sure you want to exit? (y/n)" \
    "run-shell 'tmux kill-session -t \$SESSION_NAME'"

tmux set -g prefix C-a  
tmux unbind C-b
tmux bind C-c run-shell "tmux kill-session -t \$SESSION_NAME"

# Bind arrow keys for cycling through panes
tmux bind-key -n Up select-pane -U
tmux bind-key -n Down select-pane -D
tmux bind-key -n Left select-pane -L
tmux bind-key -n Right select-pane -R

tmux set-option -g status on
tmux set-option -g status-interval 5
tmux set-option -g status-bg black
tmux set-option -g status-fg white
tmux set-option -g status-left ''
tmux set-option -g status-right 'F1: Help | F10: Exit'

tmux attach -t \$SESSION_NAME


SCRIPT;
        if (file_put_contents($scriptPath, $scriptContent) === false) {
            $this->output->writeln("<error>Failed to create devServer.sh script.</error>");
            return false;
        }

        if (!chmod($scriptPath, 0755)) {
            $this->output->writeln("<error>Failed to make devServer.sh executable.</error>");
            return false;
        }

        $this->output->writeln("<info>✓ devServer.sh script added successfully at {$scriptPath}.</info>");
        return true;
    }
}

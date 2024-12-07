<?php

namespace Ngfw\LaravelStackInstaller\Installers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Ngfw\LaravelStackInstaller\Helpers\DatabaseHelper;

class NextBreezeInstaller
{
    protected $projectName;
    protected $dbHost;
    protected $dbUser;
    protected $dbPassword;
    protected $output;

    protected $breezeNextURL = "https://github.com/laravel/breeze-next.git";
    protected $manifestFile = '../Manifests/next.js.json';

    public function __construct($projectName, $dbHost, $dbUser, $dbPassword, OutputInterface $output)
    {
        $this->projectName = $projectName;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->output = $output;
    }

    public function run()
    {
        $projectDir = getcwd() . '/' . $this->projectName;
        $frontendDir = $projectDir . '/www';

        $this->output->writeln("<info>→ Setting up Next.js in {$frontendDir}...</info>");

        try {
            // Load manifest
            $manifest = json_decode(file_get_contents(dirname(__FILE__) . "/" . $this->manifestFile), true);

            if (!$manifest) {
                $this->output->writeln("<error>Failed to load manifest file.</error>");
                return false;
            }

            // Loop through the steps in the manifest
            foreach ($manifest['steps'] as $step) {
                $method = 'handle' . ucfirst($step['name']);
                if (method_exists($this, $method)) {
                    $this->output->writeln("<info>{$step['message']}...</info>");
                    if (!$this->$method($step)) {
                        return false;
                    }
                }
            }

            $this->output->writeln("<info>✓ Installation completed successfully!</info>");
        } catch (\Exception $e) {
            $this->output->writeln("<error>Installation failed: {$e->getMessage()}</error>");
            return false;
        }

        return true;
    }

    protected function handleCreateEmptyLogFile($step)
    {
        $logMessage = "Laravel Installation Successfully";

        $this->output->writeln("<info>→ Running Laravel artisan command: php artisan tinker --execute=\"Log::info('" . $logMessage . "');\"</info>");

        $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessage . "');\"");
        
        return true;
    }


    protected function handleSetupDatabase($step)
    {
        return $this->configureDatabase();
    }

    protected function handleSetupLaravelBackend($step)
    {
        return $this->setupLaravelBackend();
    }

    protected function handleSetupNextFrontend($step)
    {
        return $this->setupNextFrontend();
    }

    protected function handleInstallNpm($step)
    {
        return $this->installNPMOnFrontend();
    }

    protected function handleUpdateEnvFile($step)
    {
        return $this->updateEnvFile();
    }

    protected function handleInitializeGit($step)
    {
        return $this->initializeGit();
    }

    protected function handleRunArtisanCommands($step)
    {
        foreach ($step['commands'] as $command) {
            if (!$this->runArtisanCommand($command)) {
                return false;
            }
        }
        return true;
    }

    protected function handleAddDevServerScript($step)
    {
        return $this->addDevServerScript();
    }

    protected function configureDatabase()
    {
        $dbHelper = new DatabaseHelper($this->dbHost, $this->dbUser, $this->dbPassword);

        if ($dbHelper->databaseExists($this->projectName)) {
            $this->output->writeln("<comment>Database '{$this->projectName}' already exists.</comment>");
            $this->output->writeln("<comment>Skipping database creation.</comment>");
            return true;
        }

        $this->output->writeln("<info>Creating database '{$this->projectName}'...</info>");
        if (!$dbHelper->createDatabase($this->projectName)) {
            $this->output->writeln("<error>Failed to create database '{$this->projectName}'.</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Database configuration complete.</info>");
        return true;
    }

    protected function setupLaravelBackend()
    {
        $this->output->writeln("<info>→  Setting up Laravel API backend...</info>");

        $process = Process::fromShellCommandline("composer create-project laravel/laravel {$this->projectName}/api");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to create Laravel project: {$process->getErrorOutput()}</error>");
            return false;
        }

        $process = Process::fromShellCommandline("cd {$this->projectName}/api && composer require laravel/breeze --dev");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to install laravel/breeze: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Laravel project created.</info>");
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

        $this->output->writeln("<info>✓ Next.js project created.</info>");
        return true;
    }
    protected function installNPMOnFrontend()
    {
        $this->output->writeln("<info>→  Install frontend...</info>");

        $process = Process::fromShellCommandline("cd {$this->projectName}/www && npm install");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to run NPM INSTALL: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ NPM Packages insalled sucessfully</info>");
        return true;
    }

    protected function updateEnvFile()
    {
        $envFile = getcwd() . "/{$this->projectName}/api/.env";

        // Check if the .env file exists
        if (!file_exists($envFile)) {
            $this->output->writeln("<error>.env file not found in {$envFile}.</error>");
            return false;
        }

        $this->output->writeln("<info>Updating .env file with NEXT_PUBLIC_BACKEND_URL...</info>");

        // Read the .env file
        $envContent = file_get_contents($envFile);

        // Check if the variable already exists, and update it
        if (strpos($envContent, 'NEXT_PUBLIC_BACKEND_URL=') !== false) {
            $envContent = preg_replace('/NEXT_PUBLIC_BACKEND_URL=.*/', 'NEXT_PUBLIC_BACKEND_URL=http://localhost:8000', $envContent);
        } else {
            // If it doesn't exist, add the line at the end of the file
            $envContent .= "\nNEXT_PUBLIC_BACKEND_URL=http://localhost:8000\n";
        }

        // Write the modified content back to the .env file
        file_put_contents($envFile, $envContent);

        $this->output->writeln("<info>.env file updated successfully.</info>");
        return true;
    }


    protected function runArtisanCommand($command)
    {
        $this->output->writeln("<info>→  Running php artisan {$command}...</info>");

        $projectDir = getcwd() . "/{$this->projectName}/api";

        if ($command === 'storage:link') {
            // Fix permissions before running storage:link
            $this->output->writeln("<info>Fixing permissions for storage and bootstrap/cache...</info>");
            $this->fixStoragePermissions($projectDir);
        }

        $process = Process::fromShellCommandline("php artisan {$command}", $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to run php artisan {$command}: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ php artisan {$command} executed successfully.</info>");
        return true;
    }
    protected function fixStoragePermissions($projectDir)
    {
        $commands = [
            "find {$projectDir} -type f -exec chmod 664 {} \\;",
            "find {$projectDir} -type d -exec chmod 775 {} \\;",
            "chgrp -Rf www-data {$projectDir}/storage {$projectDir}/bootstrap/cache",
            "chmod -Rf ug+rwx {$projectDir}/storage {$projectDir}/bootstrap/cache",
            "chmod -Rf 775 {$projectDir}/storage/ {$projectDir}/bootstrap/"
        ];

        foreach ($commands as $command) {
            $this->output->writeln("<info>→  Executing: {$command}</info>");
            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->output->writeln("<error>Failed to execute: {$command}</error>");
            }
        }

        $this->output->writeln("<info>✓ Permissions for storage and bootstrap/cache have been fixed.</info>");
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
    echo "Error: tmux is not installed. Please install tmux (and try again."
    exit 1
fi

CURRENT_DIR=$(cd "\$(dirname "\$0")" && pwd)

SESSION_NAME="dev_server"
API_DIR="\$CURRENT_DIR/api"
WWW_DIR="\$CURRENT_DIR/www"
LOG_FILE="\$API_DIR/storage/logs/laravel.log"

tmux new-session -d -s \$SESSION_NAME

tmux split-window -h -t \$SESSION_NAME       
tmux split-window -v -t \$SESSION_NAME:0.0  
tmux send-keys -t \$SESSION_NAME:0.0 "cd \$API_DIR && php artisan serve" Enter
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

    protected function initializeGit()
    {
        $projectDir = getcwd() . "/{$this->projectName}/api";

        // Initialize Git repository
        $this->output->writeln("<info>→  Initializing Git repository...</info>");
        $process = Process::fromShellCommandline("git init", $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to initialize Git repository: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Git repository initialized successfully.</info>");

        // Add all files to the Git index
        $process = Process::fromShellCommandline("git add .", $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to add files to Git: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Files added to Git index.</info>");

        // Commit the changes
        $commitMessage = "Initial commit with Laravel + Next.js Breeze stack";
        $process = Process::fromShellCommandline("git commit -m '{$commitMessage}'", $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to commit files to Git: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Initial commit created successfully.</info>");


        return true;
    }
}

<?php

namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;
use Ngfw\LaravelStack\Helpers\DatabaseHelper;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Installer
{
    protected string $version = '1.0';
    protected string $stack;
    protected string $projectName;
    protected string $dbHost;
    protected string $dbUser;
    protected string $dbPassword;
    protected ?string $backendSubDirectory;
    protected ?string $frontendSubDirectory;
    protected string $manifestFile;
    protected object $output;
    protected array $finalNotes;

    public function __construct($stack, $projectName, $dbHost, $dbUser, $dbPassword, OutputInterface $output, $backendSubDirectory = null, $frontendSubDirectory = null)
    {
        $this->stack = $stack;
        $this->projectName = $projectName;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->output = $output;
        $this->backendSubDirectory = $backendSubDirectory;
        $this->frontendSubDirectory = $frontendSubDirectory;
    }
    /**
     * Runs the installation process.
     *
     * @return bool
     */
    public function run(SymfonyStyle $io)
    {
        $this->output->writeln("<info>Starting Installation process!</info>");
        try {
            $manifestFile = realpath(dirname(__FILE__) . "/../") . "{$this->manifestFile}";
            if (!file_exists($manifestFile)) {
                throw new \Exception("Manifest file not found.");
            }

            if (file_exists($this->getBackendDirectory())) {
                throw new \Exception("/{$this->projectName} directory already exists!");
            }

            $manifest = json_decode(file_get_contents($manifestFile), true);
            $this->validateManifest($manifest);

            foreach ($manifest['steps'] as $k => $step) {
                if (!$this->executeStep($step, count($manifest['steps']), $k + 1)) {
                    return false;
                }
            }


            $this->output->writeln("<info>Installation completed successfully!</info>");

            $io->success(sprintf('%s installation completed successfully!', $this->stack));

            $notes = [
                'Your new stack has been successfully installed in the following directory:',
                "/{$this->projectName}",
                '',
            ];

            if (!empty($this->backendSubDirectory)) {
                $notes[] = 'The backend server has been set up and is ready to run.';
            }

            if (!empty($this->frontendSubDirectory)) {
                $notes[] = 'The frontend development server has been set up and is ready to run.';
            }

            $notes[] = 'To start your development environment, a dev server script has been included.';
            $notes[] = 'Follow these steps to run it:';
            $notes[] = "1. Navigate to your project directory:";
            $notes[] = "   cd {$this->projectName}/";
            $notes[] = "2. Run the dev server script:";
            $notes[] = "   ./devServer.sh";
            $notes[] = "Happy Coding!";
            $notes[] = '';

            if (!empty($this->backendSubDirectory) || !empty($this->frontendSubDirectory)) {
                $notes[] = 'The dev server script will start the following components:';
                if (!empty($this->backendSubDirectory)) {
                    $notes[] = '- Backend server (Laravel).';
                }
                if (!empty($this->frontendSubDirectory)) {
                    $notes[] = '- Frontend development server.';
                }
                $notes[] = '- Log viewer for the backend server.';
            }

            $io->note($this->finalNotes ?? $notes);

            return true;
        } catch (\Exception $e) {
            $this->logError("Installation failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Executes a specific installation step.
     *
     * @param array $step
     * @return bool
     */
    protected function executeStep(array $step, int $totalSteps, int $currentStep)
    {
        $this->output->writeln("<info>► [{$currentStep}/{$totalSteps}] {$step['message']}</info>");
        $method = $step['name'];
        if (!method_exists($this, $method)) {
            throw new \Exception("Undefined step method: {$method}");
        }

        try {
            return $this->$method($step);
        } catch (\Exception $e) {
            $this->logError("Step '$method' failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validates the installation manifest.
     *
     * @param array $manifest
     * @return bool
     * @throws \Exception
     */
    protected function validateManifest(array $manifest)
    {
        if (!isset($manifest['steps']) || !is_array($manifest['steps'])) {
            throw new \Exception("Invalid manifest: 'steps' must be an array.");
        }

        foreach ($manifest['steps'] as $step) {
            if (!isset($step['name'], $step['message'])) {
                throw new \Exception("Each step must have a 'name' and 'message'.");
            }
        }

        return true;
    }

    protected function runArtisanCommands($step)
    {
        foreach ($step['commands'] as $command) {
            if (!$this->runArtisanCommand($command)) {
                return false;
            }
        }
        return true;
    }
    /**
     * Executes a shell command.
     *
     * @param string $command
     * @param string|null $workingDir
     * @return bool
     */
    protected function runCommand(string $command, ?string $workingDir = null)
    {
        $process = Process::fromShellCommandline($command, $workingDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->logError("Command failed: {$command}. Error: " . $process->getErrorOutput());
            return false;
        }

        $this->output->writeln("<info>{$process->getOutput()}</info>");
        return true;
    }

    protected function createEmptyLogFile()
    {
        $logMessage = "Laravel Installation Successfully";

        $this->output->writeln("<info>→ Running Laravel artisan command: php artisan tinker --execute=\"Log::info('" . $logMessage . "');\"</info>");

        return $this->runArtisanCommand("tinker --execute=\"Log::info('" . $logMessage . "');\"");
    }

    /**
     * Creates and configures the database.
     *
     * @return bool
     */
    protected function setupDatabase()
    {
        $dbHelper = new DatabaseHelper($this->dbHost, $this->dbUser, $this->dbPassword);

        if ($dbHelper->databaseExists($this->projectName)) {
            $this->output->writeln("<comment>Database '{$this->projectName}' already exists. Skipping creation.</comment>");
            return true;
        }

        $this->output->writeln("<info>Creating database '{$this->projectName}'...</info>");
        if (!$dbHelper->createDatabase($this->projectName)) {
            $this->output->writeln("<error>Failed to create database '{$this->projectName}'.</error>");
            return false;
        }

        $this->output->writeln("<info>Database '{$this->projectName}' created successfully.</info>");
        return true;
    }

    /**
     * setup breeze package
     * 
     * @return bool
     */
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

        $this->output->writeln("<info>✓ Laravel Breeze installed.</info>");
        return true;
    }

    /**
     * Updates the .env file.
     *
     * @return bool
     */
    protected function updateEnvFile()
    {
        $envFile = $this->getBackendDirectory() . "/.env";

        if (!file_exists($envFile)) {
            $this->output->writeln("<error>.env file not found in {$envFile}.</error>");
            return false;
        }

        $envContent = file_get_contents($envFile);
        $this->replaceInFile($envFile, 'APP_NAME=.*', "APP_NAME=\"{$this->projectName}\"");
        $this->replaceInFile($envFile, 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql');
        $this->replaceInFile($envFile, '# DB_DATABASE=.*', "DB_DATABASE={$this->projectName}");
        $this->replaceInFile($envFile, '# DB_USERNAME=.*', "DB_USERNAME={$this->dbUser}");
        $this->replaceInFile($envFile, '# DB_PASSWORD=.*', "DB_PASSWORD={$this->dbPassword}");
        $this->replaceInFile($envFile, '# DB_HOST=.*', "DB_HOST={$this->dbHost}");
        $this->replaceInFile($envFile, 'APP_URL=.*', "APP_URL=http://127.0.0.1:8000");
        if ($this->stack === "Next.js Breeze Stack") {
            if (strpos($envContent, 'NEXT_PUBLIC_BACKEND_URL=') !== false) {
                $envContent = preg_replace('/NEXT_PUBLIC_BACKEND_URL=.*/', 'NEXT_PUBLIC_BACKEND_URL=http://localhost:8000', $envContent);
            } else {
                $envContent .= "\nNEXT_PUBLIC_BACKEND_URL=http://localhost:8000\n";
            }
        }

        file_put_contents($envFile, $envContent);
        $this->output->writeln("<info>.env file updated successfully.</info>");
        return true;
    }
    protected function replaceInFile($filePath, $searchPattern, $replacement)
    {
        $fileContents = file_get_contents($filePath);
        $updatedContents = preg_replace("/^{$searchPattern}/m", $replacement, $fileContents);
        file_put_contents($filePath, $updatedContents);
    }


    protected function setupLaravelBackend()
    {
        $this->output->writeln("<info>→  Setting up Laravel backend...</info>");

        $fullPath = $this->projectName . ($this->backendSubDirectory ? "/{$this->backendSubDirectory}" : '');

        $process = Process::fromShellCommandline("composer create-project laravel/laravel {$fullPath}");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Error setting up Laravel backend: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>Laravel backend setup completed at {$fullPath}.</info>");
        return true;
    }
    protected function installNPM()
    {
        $this->output->writeln("<info>→  Running npm install on Laravel installation...</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && npm install");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to run NPM INSTALL ({$this->backendSubDirectory}): {$process->getErrorOutput()}</error>");
            return false;
        }

        if (!empty($this->backendSubDirectory) && !empty($this->frontendSubDirectory)) {
            $this->output->writeln("<info>→  Running npm install on Frontend installation ({$this->frontendSubDirectory})...</info>");
            $frontendPath = getcwd() . "/{$this->projectName}/{$this->frontendSubDirectory}";
            $process = Process::fromShellCommandline("cd {$frontendPath} && npm install");
            $process->run();

            if (!$process->isSuccessful()) {
                $this->output->writeln("<error>Failed to run NPM INSTALL ({$this->frontendSubDirectory}): {$process->getErrorOutput()}</error>");
                return false;
            }
        }

        $this->output->writeln("<info>✓ NPM Packages insalled sucessfully</info>");
        return true;
    }

    protected function installConfetti()
    {
        $this->output->writeln("<info>→  Setting up @tsparticles/confetti</info>");

        $frontendPath = getcwd() . "/{$this->projectName}/" . (
            !empty($this->frontendSubDirectory)
            ? $this->frontendSubDirectory
            : ""
        );
        $process = Process::fromShellCommandline("cd {$frontendPath} && npm install @tsparticles/confetti");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to install @tsparticles/confetti: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Confetti installed.</info>");
        return true;
    }

    protected function setupTailwind()
    {
        $projectPath = $this->getBackendDirectory();

        $process = Process::fromShellCommandline("cd $projectPath && npm install -D tailwindcss postcss autoprefixer && npx tailwindcss init");
        $process->run();


        $tailwindConfigPath = "$projectPath/tailwind.config.js";
        file_put_contents(
            $tailwindConfigPath,
            <<<EOT
/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.vue',
    './resources/**/*.{ts,jsx,js,tsx}',
    './resources/react/**/*.{ts,jsx,js,tsx}',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
EOT
        );

        $appCssPath = "$projectPath/resources/css/app.css";
        file_put_contents(
            $appCssPath,
            <<<EOT
@tailwind base;
@tailwind components;
@tailwind utilities;
EOT
        );
        return true;
    }

    /**
     * Resolves the backend directory path.
     *
     * @return string
     */
    protected function getBackendDirectory()
    {
        return getcwd() . "/{$this->projectName}" . ($this->backendSubDirectory ? "/{$this->backendSubDirectory}" : '');
    }

    public function installInertia()
    {
        $this->output->writeln("<info>→  Setting up Laravel inertia...</info>");

        $fullPath = $this->projectName . ($this->backendSubDirectory ? "/{$this->backendSubDirectory}" : '');

        $process = Process::fromShellCommandline("cd {$fullPath} && composer require inertiajs/inertia-laravel");
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Error setting up Laravel inertia: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Laravel inertia setup completed.</info>");
        return true;
    }

    protected function runArtisanCommand($command)
    {
        $projectDir = $this->getBackendDirectory();

        $this->output->writeln("<info>→ Running php artisan {$command}...</info>");

        if ($command === 'storage:link') {
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
        $command = "chmod -Rf 775 {$projectDir}/storage {$projectDir}/bootstrap/cache";
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to execute: {$command}</error>");
            return false;
        }


        $this->output->writeln("<info>✓ Permissions for storage and bootstrap/cache have been fixed.</info>");
        return true;
    }

    /**
     * Recursively copy files and directories.
     *
     * @param string $source Source directory path
     * @param string $destination Destination directory path
     * @return void
     */
    protected function copyDirectory(string $source, string $destination): void
    {
        // Ensure the source directory exists
        if (!is_dir($source)) {
            throw new \Exception("Source directory does not exist: $source");
        }

        // Create the destination directory if it doesn't exist
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        // Iterate through the items in the source directory
        $items = scandir($source);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $sourcePath = rtrim($source, '/') . '/' . $item;
            $destinationPath = rtrim($destination, '/') . '/' . $item;

            if (is_dir($sourcePath)) {
                // Recursively copy subdirectories
                $this->copyDirectory($sourcePath, $destinationPath);
            } else {
                // Copy files
                copy($sourcePath, $destinationPath);
            }
        }
    }

    /**
     * Copy file
     * @param string $source
     * @param string $destination
     * @throws \RuntimeException
     * @return void
     */
    protected function copyFile(string $source, string $destination): void
    {
        if (!file_exists($source)) {
            throw new \RuntimeException("The source file '{$source}' does not exist.");
        }

        if (!is_readable($source)) {
            throw new \RuntimeException("The source file '{$source}' is not readable.");
        }

        $destinationDir = dirname($destination);

        // Ensure the destination directory exists
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true) && !is_dir($destinationDir)) {
                throw new \RuntimeException("Failed to create destination directory '{$destinationDir}'.");
            }
        }

        // Attempt to copy the file
        if (!@copy($source, $destination)) {
            $error = error_get_last();
            throw new \RuntimeException("Failed to copy file: " . ($error['message'] ?? 'Unknown error'));
        }
    }

    /**
     * Logs an error message.
     *
     * @param string $message
     */
    protected function logError(string $message)
    {
        $logFile = realpath(dirname(__FILE__) . '/../../') . '/installer_errors.log';
        file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
        $this->output->writeln("<error>{$message}</error>");
        return true;
    }

    protected function addDevServerScript()
    {
        $this->output->writeln("<info>→  Adding devServer.sh script...</info>");

        $projectDir = "/{$this->projectName}";
        $scriptPath = getcwd() . $projectDir . '/devServer.sh';

        $backendSubDir = isset($this->backendSubDirectory) ? $this->backendSubDirectory : '';
        $frontendSubDir = !empty($this->backendSubDirectory) && !empty($this->frontendSubDirectory)
            ? $this->frontendSubDirectory
            : '';

        if ($backendSubDir) {
            $apiDir = $backendSubDir;
            $wwwDir = $frontendSubDir;
        } else {
            $apiDir = "";
            $wwwDir = "";
        }

        $scriptContent = <<<SCRIPT
        #!/bin/bash

        ################################################################################
        #                                                                              #
        #                          Laravel Stack Dev Server                            #
        #                                                                              #
        #   This script automates the setup of a development environment using tmux.   #
        ################################################################################

        if ! command -v tmux &> /dev/null; then
            echo "Error: tmux is not installed. Please install tmux and try again."
            exit 1
        fi

        CURRENT_DIR=$(cd "$(dirname "$0")" && pwd)

        SESSION_NAME="dev_server"
        API_DIR="\$CURRENT_DIR/$apiDir"
        LOG_FILE="\$API_DIR/storage/logs/laravel.log"
        WWW_DIR="\$CURRENT_DIR/$wwwDir"

        # Start a new tmux session and name the first window
        tmux new-session -d -s \$SESSION_NAME -n "Laravel Stack Dev Server 1.0"

        # Pane 0.0: Laravel API
        tmux select-pane -t \$SESSION_NAME:0.0 -T "Laravel API"
        SCRIPT;
        if ($this->stack !== 'API-Only') {
            $scriptContent .= <<<SCRIPT
            # Pane 0.1: Frontend Dev Server
            tmux split-window -h -t \$SESSION_NAME:0.0 # Split horizontally for frontend dev server
            tmux select-pane -t \$SESSION_NAME:0.1 -T "Frontend Dev Server"

            # Pane 0.2: Log Viewer
            tmux split-window -v -t \$SESSION_NAME:0.0 # Split vertically for log viewer
            tmux select-pane -t \$SESSION_NAME:0.2 -T "Log Viewer"

            # Initial commands for each pane
            tmux send-keys -t \$SESSION_NAME:0.0 "cd \$API_DIR && php artisan serve" Enter
            tmux send-keys -t \$SESSION_NAME:0.1 "cd \$WWW_DIR && npm run dev" Enter
            tmux send-keys -t \$SESSION_NAME:0.2 "tail -f \$LOG_FILE" Enter

            SCRIPT;
        } else {
            $scriptContent .= <<<SCRIPT
            # Pane 0.1: Log Viewer
            tmux split-window -v -t \$SESSION_NAME:0.0 # Split vertically for log viewer
            tmux select-pane -t \$SESSION_NAME:0.2 -T "Log Viewer"

            # Initial commands for each pane
            tmux send-keys -t \$SESSION_NAME:0.0 "cd \$API_DIR && php artisan serve" Enter
            tmux send-keys -t \$SESSION_NAME:0.1 "tail -f \$LOG_FILE" Enter
            
            SCRIPT;
        }

        if ($this->stack !== 'API-Only') {
            $scriptContent .= <<<SCRIPT
            
            tmux bind-key -n F2 run-shell "tmux send-keys -t \$SESSION_NAME:0.0 C-c 'cd \$API_DIR && php artisan serve' Enter; tmux send-keys -t \$SESSION_NAME:0.1 C-c 'cd \$WWW_DIR && npm run dev' Enter"
            SCRIPT;
        }else{
            $scriptContent .= <<<SCRIPT
            
            tmux bind-key -n F2 run-shell "tmux send-keys -t \$SESSION_NAME:0.0 C-c 'cd \$API_DIR && php artisan serve' Enter;"
            SCRIPT;
        }

        $scriptContent .= <<<SCRIPT
        
        tmux bind-key -n F1 display-message "Use the arrow keys to navigate between panes, press F2 to restart server, press F10 to quit."
        
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

        # Status bar customization
        tmux set-option -g status on
        tmux set-option -g status-interval 5
        tmux set-option -g status-bg black
        tmux set-option -g status-fg white
        tmux set-option -g status-left ''
        tmux set-option -g status-right 'F1: Help | F2: Restart Servers | F10: Exit'

        # Attach to the session
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

        $this->output->writeln("<info>✓ devServer.sh script added successfully at {$projectDir}.</info>");
        return true;
    }


    protected function initializeGit()
    {
        $projectDir = $this->getBackendDirectory();

        $this->output->writeln("<info>→  Initializing Git repository...</info>");
        $process = Process::fromShellCommandline("git init", $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to initialize Git repository: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Git repository initialized successfully.</info>");

        $process = Process::fromShellCommandline("git add .", $projectDir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Failed to add files to Git: {$process->getErrorOutput()}</error>");
            return false;
        }

        $this->output->writeln("<info>✓ Files added to Git index.</info>");

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

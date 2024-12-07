<?php 
namespace Ngfw\LaravelStackInstaller\Installers;

use Ngfw\LaravelStackInstaller\Helpers\DatabaseHelper;
use Symfony\Component\Console\Output\OutputInterface;

class ReactTailwindStackInstaller
{
    protected $projectName;
    protected $dbHost;
    protected $dbUser;
    protected $dbPassword;
    protected $output;

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
        $this->output->writeln("<info>Setting up React + Tailwind CSS stack for '{$this->projectName}'...</info>");

        try {
            $this->output->writeln("<info>Creating Laravel project...</info>");
            $this->runShellCommand("composer create-project laravel/laravel {$this->projectName}");

            $this->configureEnv();

            $dbHelper = new DatabaseHelper($this->dbHost, $this->dbUser, $this->dbPassword);
            if (!$dbHelper->createDatabase($this->projectName)) {
                $this->output->writeln("<error>Database '{$this->projectName}' already exists or cannot be created.</error>");
                return false;
            }

            $projectPath = getcwd() . "/{$this->projectName}/";
            $this->output->writeln("<info>Installing React...</info>");
            $this->runShellCommand("cd {$projectPath} && npm install react react-dom react-router-dom");

            $this->output->writeln("<info>Configuring Tailwind CSS...</info>");
            $this->runShellCommand("cd {$projectPath} && npm install -D tailwindcss postcss autoprefixer && npx tailwindcss init");

            $this->output->writeln("<info>Running migrations...</info>");
            $this->runShellCommand("cd {$projectPath} && php artisan migrate");

            $this->output->writeln("<info>✓ React + Tailwind CSS stack setup completed!</info>");
            return true;
        } catch (\Exception $e) {
            $this->output->writeln("<error>An error occurred: {$e->getMessage()}</error>");
            return false;
        }
    }

    protected function configureEnv()
    {
        $envPath = getcwd() . "/{$this->projectName}/.env";
        if (!file_exists($envPath)) {
            throw new \Exception("The .env file for the project '{$this->projectName}' was not found.");
        }

        $this->replaceInFile($envPath, 'APP_NAME=.*', "APP_NAME=\"{$this->projectName}\"");
        $this->replaceInFile($envPath, 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql');
        $this->replaceInFile($envPath, '# DB_DATABASE=.*', "DB_DATABASE={$this->projectName}");
        $this->replaceInFile($envPath, '# DB_USERNAME=.*', "DB_USERNAME={$this->dbUser}");
        $this->replaceInFile($envPath, '# DB_PASSWORD=.*', "DB_PASSWORD={$this->dbPassword}");
        $this->replaceInFile($envPath, '# DB_HOST=.*', "DB_HOST={$this->dbHost}");
        $this->replaceInFile($envPath, 'APP_URL=.*', "APP_URL=http://127.0.0.1:8000");
    }

    protected function runShellCommand($command)
    {
        $output = shell_exec($command);
        if ($output === null) {
            throw new \Exception("Command failed: $command");
        }
        $this->output->writeln($output);
    }

    protected function replaceInFile($filePath, $searchPattern, $replacement)
    {
        $fileContents = file_get_contents($filePath);
        $updatedContents = preg_replace("/^{$searchPattern}/m", $replacement, $fileContents);
        file_put_contents($filePath, $updatedContents);
    }
}

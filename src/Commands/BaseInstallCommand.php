<?php

namespace Ngfw\LaravelStack\Commands;

use Ngfw\LaravelStack\Helpers\InputHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseInstallCommand extends Command
{
    protected string $title;
    protected string $installerClass;
    protected ?string $backendSubDirectory = null;
    protected ?string $frontendSubDirectory = null;

    protected function configure()
    {
        $this
            ->addOption('project', null, InputOption::VALUE_OPTIONAL, 'The project name or domain')
            ->addOption('db.host', null, InputOption::VALUE_OPTIONAL, 'The database host', 'localhost')
            ->addOption('db.user', null, InputOption::VALUE_OPTIONAL, 'The database user', 'root')
            ->addOption('db.password', null, InputOption::VALUE_OPTIONAL, 'The database password');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputHelper = new InputHelper($io);

        $projectName = $input->getOption('project');
        $dbHost = $input->getOption('db.host');
        $dbUser = $input->getOption('db.user');
        $dbPassword = $input->getOption('db.password');
        
        if (!$projectName  || $dbPassword === null) {
            $io->warning('Some required options are missing or using default values. Falling back to interactive prompts.');
            [$projectName, $dbHost, $dbUser, $dbPassword] = $inputHelper->collectBasicProjectData();
        }

        $io->title(sprintf('<fg=cyan>Starting %s Installation...</fg=cyan>', $this->title));

        try {
            $installerClass = $this->installerClass;
            $installer = new $installerClass(
                $this->title,
                $projectName,
                $dbHost,
                $dbUser,
                $dbPassword,
                $output,
                $this->backendSubDirectory,
                $this->frontendSubDirectory
            );

            if (!$installer->run($io)) {
                $io->error(sprintf('%s installation failed.', $this->title));
                return Command::FAILURE;
            }

            return Command::SUCCESS;
        } catch (\RuntimeException $e) {
            $io->error(sprintf('Input error: %s', $e->getMessage()));
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error(sprintf('An unexpected error occurred: %s', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}

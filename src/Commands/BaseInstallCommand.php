<?php

namespace Ngfw\LaravelStackInstaller\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ngfw\LaravelStackInstaller\Helpers\InputHelper;

abstract class BaseInstallCommand extends Command
{
    protected string $title;
    protected string $installerClass;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputHelper = new InputHelper($io);

        try {
            [$projectName, $dbHost, $dbUser, $dbPassword] = $inputHelper->collectBasicProjectData();

            $io->title(sprintf('<fg=cyan>Starting %s Installation...</fg=cyan>', $this->title));

            $installerClass = $this->installerClass;
            $installer = new $installerClass($projectName, $dbHost, $dbUser, $dbPassword, $output);

            if (!$installer->run()) {
                $io->error(sprintf('%s installation failed.', $this->title));
                return Command::FAILURE;
            }

            $io->success(sprintf('%s installation completed successfully!', $this->title));
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

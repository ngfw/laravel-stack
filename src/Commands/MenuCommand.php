<?php

namespace Ngfw\LaravelStack\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[AsCommand(name: 'Menu')]
class MenuCommand extends Command
{
    
    protected static $menu = [
        '▲ Next.js + Breeze' => [
            "message" => "Installing Next.js + Breeze stack...",
            "description" => "Next.js + Laravel API + Breeze: A seamless setup for modern web apps using Next.js as the frontend and Laravel as the backend.",
            "commandName" => 'install:next-breeze',
        ],
        '⚛ React + Tailwind Stack' => [
            "message" => "Installing React + Tailwind stack...",
            "description" => "React + Tailwind CSS: A stack for building modern frontend interfaces with React and styling with Tailwind CSS.",
            "commandName" => 'install:react-tailwind',
        ],
        '✧ Vue + Inertia.js + Tailwind Stack' => [
            "message" => "Installing Vue + Tailwind stack...",
            "description" => "Vue + Tailwind CSS: Combines Vue.js for dynamic UI components with Tailwind CSS for styling.",
            "commandName" => 'install:vue-tailwind',
        ],
        'τ TALL Stack' => [
            "message" => "Installing TALL stack...",
            "description" => "TALL Stack: Combines TailwindCSS, AlpineJS, Laravel, and FilamentPHP for a clean and productive UI/UX workflow.",
            "commandName" => 'install:tall-stack',
        ],
        '◉ Livewire + Tailwind Stack' => [
            "message" => "Installing Livewire + Tailwind stack...",
            "description" => "Livewire + Tailwind CSS: A powerful stack for dynamic and real-time UIs with Livewire and Tailwind CSS.",
            "commandName" => 'install:livewire-tailwind',
        ],
        '◯ API-Only Stack' => [
            "message" => "Installing API-Only stack...",
            "description" => "API-Only Stack: A lightweight Laravel setup for purely API-driven applications.",
            "commandName" => 'install:api-only',
        ],
        '⬡ GraphQL Stack' => [
            "message" => "Installing GraphQL stack...",
            "description" => "GraphQL Stack: Integrates GraphQL with Laravel for flexible API queries.",
            "commandName" => 'install:graphql-stack',
        ],
        '← Exit' => [
            "message" => "Exiting the installer...",
            "description" => "Exit the interactive installer. \n =========================== \n",
            "commandName" => Command::SUCCESS,
        ],
    ];

    protected function configure()
    {
        $this->setDescription('Interactive Laravel Stack Installer')
            ->addOption('project', null, InputOption::VALUE_REQUIRED, 'The project name or domain')
            ->addOption('db.host', null, InputOption::VALUE_REQUIRED, 'The database host', 'localhost')
            ->addOption('db.user', null, InputOption::VALUE_REQUIRED, 'The database user', 'root')
            ->addOption('db.password', null, InputOption::VALUE_OPTIONAL, 'The database password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('<fg=cyan>Laravel Stack Installer 1.0</fg=cyan>');
        $io->text('<fg=yellow>Select a stack to install:</fg=yellow>');

        foreach (self::$menu as $key => $details) {
            if($key !== '← Exit'){
                $io->text(sprintf("<info>%s</info>: \n - %s\n", $key, $details['description']));
            }
        }

        $question = new ChoiceQuestion(
            'Please choose an installation option',
            array_keys(self::$menu),
            7
        );
        $question->setErrorMessage('Option %s is invalid.');
        $choice = $io->askQuestion($question);

        if (isset(self::$menu[$choice])) {
            $io->success(self::$menu[$choice]['message']);

            if (self::$menu[$choice]['commandName'] !== Command::SUCCESS) {
                $commandName = self::$menu[$choice]['commandName'];
                $application = $this->getApplication();
                if ($application) {
                    $command = $application->find($commandName);

                    // Prepare arguments to pass options to the sub-command
                    $arguments = [
                        '--project' => $input->getOption('project') ?? null,
                        '--db.host' => $input->getOption('db.host') ?? null,
                        '--db.user' => $input->getOption('db.user') ?? null,
                        '--db.password' => $input->getOption('db.password') ?? null,
                    ];

                    $arrayInput = new ArrayInput($arguments);
                    
                    $exitCode = $command->run($arrayInput, $output);
                    return $exitCode;
                }
            }

            return Command::SUCCESS;
        }

        return Command::SUCCESS;
    }
}

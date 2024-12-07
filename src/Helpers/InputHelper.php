<?php

namespace Ngfw\LaravelStackInstaller\Helpers;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;

class InputHelper
{
    protected SymfonyStyle $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    /**
     * Ask a validated question.
     *
     * @param string $question
     * @param callable $validation
     * @param string|null $default
     * @return string
     */
    public function askValidated(string $question, callable $validation, ?string $default = null): string
    {
        $questionObject = new Question($question, $default);
        $questionObject->setValidator($validation);

        return $this->io->askQuestion($questionObject);
    }

    /**
     * Ask a validated hidden question (e.g., password).
     *
     * @param string $question
     * @param callable $validation
     * @return string
     */
    public function askHiddenValidated(string $question, callable $validation): string
    {
        $questionObject = new Question($question);
        $questionObject->setValidator($validation);
        $questionObject->setHidden(true);
        $questionObject->setHiddenFallback(false);

        return $this->io->askQuestion($questionObject);
    }

    public function collectBasicProjectData()
    {
        // Collect inputs using InputHelper
        $projectName = $this->askValidated(
            'Please provide a project name: ',
            fn($answer) => $this->validateNotEmpty($answer, 'Project name cannot be empty.')
        );

        $dbHost = $this->askValidated(
            'Please provide the database host: ',
            fn($answer) => $this->validateNotEmpty($answer, 'Database host cannot be empty.'),
            'localhost'
        );

        $dbUser = $this->askValidated(
            'Please provide the database user: ',
            fn($answer) => $this->validateNotEmpty($answer, 'Database user cannot be empty.'),
            'root'
        );

        $dbPassword = $this->askHiddenValidated(
            'Please provide the database password: ',
            fn($answer) => $this->validateNotEmpty($answer, 'Database password cannot be empty.')
        );
        return [$projectName, $dbHost, $dbUser, $dbPassword];
    }
    private function validateNotEmpty(string $answer, string $errorMessage): string
    {
        if (empty($answer)) {
            throw new \RuntimeException($errorMessage);
        }
        return $answer;
    }
}

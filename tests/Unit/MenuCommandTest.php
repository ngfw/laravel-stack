<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Ngfw\LaravelStack\Commands\MenuCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class MenuCommandTest extends TestCase
{
    public function testMenuDisplaysOptions()
    {
        $command = new MenuCommand();

        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $command->run($input, $output);

        $outputText = $output->fetch();

        $this->assertStringContainsString('Next.js + Breeze', $outputText);
        $this->assertStringContainsString('React + Tailwind Stack', $outputText);
        $this->assertStringContainsString('API-Only Stack', $outputText);
        $this->assertStringContainsString('GraphQL Stack', $outputText);
    }

    public function testMenuHandlesInvalidOption()
    {
        $command = new MenuCommand();

        $input = new ArrayInput(['option' => 'invalid']);
        $output = new BufferedOutput();

        $command->run($input, $output);

        $outputText = $output->fetch();

        $this->assertStringContainsString('Invalid option', $outputText);
    }

    public function testMenuTriggersStackInstallation()
    {
        $command = new MenuCommand();

        $input = new ArrayInput(['option' => 'tall-stack']);
        $output = new BufferedOutput();

        $command->run($input, $output);

        $outputText = $output->fetch();

        $this->assertStringContainsString('Installing TALL Stack', $outputText);
    }
}
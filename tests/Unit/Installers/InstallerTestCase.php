<?php
namespace Tests\Unit\Installers;

use PHPUnit\Framework\TestCase;

abstract class InstallerTestCase extends TestCase
{
    abstract protected function getInstaller();
    abstract protected function getStackName();

    public function testInstallerCreatesRequiredFiles()
    {
        $installer = $this->getInstaller();

        $mockProjectPath = '/tmp/mock-' . $this->getStackName();
        mkdir($mockProjectPath, 0777, true);

        $installer->install($mockProjectPath);

        $this->assertDirectoryExists($mockProjectPath . '/resources');
        $this->assertFileExists($mockProjectPath . '/package.json');

        array_map('unlink', glob("$mockProjectPath/*"));
        rmdir($mockProjectPath);
    }

    public function testInstallerOutputsSuccessMessage()
    {
        $installer = $this->getInstaller();

        $mockProjectPath = '/tmp/mock-' . $this->getStackName();
        mkdir($mockProjectPath, 0777, true);

        ob_start();
        $installer->install($mockProjectPath);
        $output = ob_get_clean();

        $this->assertStringContainsString($this->getStackName() . ' installed successfully!', $output);

        array_map('unlink', glob("$mockProjectPath/*"));
        rmdir($mockProjectPath);
    }
}
<?php 
namespace Tests\Unit\Installers;
use Tests\Unit\Installers\InstallerTestCase;
use Ngfw\LaravelStack\Installers\TallStackInstaller;

class TallStackInstallerTest extends InstallerTestCase
{
    protected function getInstaller()
    {
        return new TallStackInstaller();
    }

    protected function getStackName()
    {
        return 'TALL Stack';
    }
}
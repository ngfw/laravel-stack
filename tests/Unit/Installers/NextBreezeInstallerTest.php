<?php 
namespace Tests\Unit\Installers;

use Tests\Unit\Installers\InstallerTestCase;
use Ngfw\LaravelStack\Installers\NextBreezeInstaller;

class NextBreezeInstallerTest extends InstallerTestCase
{
    protected function getInstaller()
    {
        return new NextBreezeInstaller();
    }

    protected function getStackName()
    {
        return 'Next.js + Breeze';
    }
}
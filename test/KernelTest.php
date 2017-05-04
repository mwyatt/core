<?php

namespace Mwyatt\Core;

class KernelTest extends \PHPUnit_Framework_TestCase
{


    public function testIt()
    {
        // $basePath = (string) (__DIR__ . '/../');
        // $kernel = new \Mwyatt\Core\Http\Kernel;
        // $kernel->setServiceProjectPath($basePath);
        // $kernel->setServicesEssential();
        // // $kernel->setServices($basePath . 'services.php');
        // // $kernel->setSettings([
        // //     'projectBaseNamespace' => 'Mwyatt\\Core\\'
        // // ]);
        // $kernel->setMiddleware([
        //     'common' => \Mwyatt\Core\Middleware\Common::class,
        //     'admin.auth' => \Mwyatt\Core\Middleware\Admin::class
        // ]);
        // $kernel->route();
    }


    public function testSetConfigData()
    {
        $kernel = new \Mwyatt\Core\Http\Kernel;
        $kernel->setConfigData(['hello' => 'world']);
        $kernel->setServicesEssential();
        $config = $kernel->getService('Config');
        $this->assertTrue($config->getSetting('hello') === 'world');
    }
}

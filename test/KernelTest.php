<?php

namespace Mwyatt\Core;

class KernelTest extends \PHPUnit_Framework_TestCase
{


    public function testSetConfigData()
    {
        $kernel = new \Mwyatt\Core\Http\Kernel;
        $kernel->setConfigData(['hello' => 'world']);
        $kernel->setServicesEssential();
        $config = $kernel->getService('Config');
        $this->assertTrue($config->getSetting('hello') === 'world');
    }


    public function testSetGetService()
    {
        $kernel = new \Mwyatt\Core\Http\Kernel;
        $kernel->setService('example', function() {
            return 'hello';
        });
        $this->assertTrue($kernel->getService('example') == 'hello');
    }
}

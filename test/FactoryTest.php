<?php

namespace Mwyatt\Core;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public $factory;


    public function __construct()
    {
        $container = new \Pimple\Container;
        $container['DefaultTemplateDirectory'] = function ($container) {
            return (string) __DIR__ . '/../' . 'template/';
        };
        $container['ViewFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\View($container['DefaultTemplateDirectory']);
        };
        $this->factory = $container['ViewFactory'];
    }


    public function testGet()
    {
        $userView = $this->factory->get('User');
        $this->assertTrue(get_class($userView) === 'Mwyatt\Core\View\User');
    }

    
    /**
     * @expectedException \Exception
     */
    public function testGetException()
    {
        $userView = $this->factory->get('Not/There');
    }
}

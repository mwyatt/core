<?php

namespace Mwyatt\Core;

class FactoryIteratorTest extends \PHPUnit_Framework_TestCase
{
    public $iteratorFactory;


    public function setUp()
    {
        $this->iteratorFactory = new \Mwyatt\Core\Factory\Iterator;
    }


    public function testGet()
    {
        $this->iteratorFactory->setDefaultNamespace('Mwyatt\\Core\\Iterator\\');
        $iterator = $this->iteratorFactory->get('Model');
        $this->assertTrue(get_class($iterator) === 'Mwyatt\Core\Iterator\Model');
    }


    /**
     * @expectedException \Exception
     */
    public function testGetException()
    {
        $iterator = $this->iteratorFactory->get('Not/There');
    }


    public function testGetFallback()
    {
        $this->iteratorFactory->setDefaultNamespace('Mwyatt\\Not\\There\\');
        $iterator = $this->iteratorFactory->get('Model');
        $this->assertTrue(get_class($iterator) === 'Mwyatt\Core\Iterator\Model');
    }
}

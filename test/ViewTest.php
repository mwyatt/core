<?php

namespace Mwyatt\Core;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    public $view;


    public function __construct()
    {
        $container = new \Pimple\Container;
        $container['View'] = function ($container) {
            return new \Mwyatt\Core\View((string) __DIR__ . '/../' . 'template/');
        };
        $this->view = $container['View'];
    }


    /**
     * @expectedException \Exception
     */
    public function testAppendTemplateDirectoryException()
    {
        $this->view->appendTemplateDirectory('not-found/');
    }


    public function testAppendTemplateDirectory()
    {
        $this->assertTrue($this->view->getTemplateDirectoriesTotal() === 1);
        $this->view->appendTemplateDirectory('template/');
        $this->assertTrue($this->view->getTemplateDirectoriesTotal() === 2);
    }


    public function testPrependTemplateDirectory()
    {
        $this->view->prependTemplateDirectory('template/');
        $this->assertTrue($this->view->getTemplateDirectoriesTotal() === 2);
    }


    public function testGetTemplate()
    {
        $this->view->offsetSet('foo', 'bar');
        $this->assertTrue('bar' === $this->view->offsetGet('foo'));
        $this->assertTrue('bar<p>test</p>' === $this->view->getTemplate('test'));
    }
}

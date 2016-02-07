<?php

namespace Mwyatt\Core;

class ViewTest extends \PHPUnit_Framework_TestCase
{


    public $pathBase;


    public $pathBasePackage = '/var/www/html/core/src/../';


    public $view;


    public function __construct()
    {
        $this->pathBase = (string) __DIR__ . '/../';
        $this->view = new \Mwyatt\Core\View;
    }


    public function testDataOffset()
    {
        $this->view->data->offsetSet('foo', 'bar');
        $this->assertEquals('bar', $this->view->data->offsetGet('foo'));
    }


    public function testPathBasePackage()
    {
        $this->assertEquals($this->pathBasePackage . 'append/', $this->view->getPathBasePackage('append/'));
    }


    public function testGetPathBase()
    {
        $this->view->setPathBase($this->pathBase);
        ;
        $this->assertEquals($this->pathBase . 'append/', $this->view->getPathBase('append/'));
    }


    public function testAppendTemplatePath()
    {
        $this->view->appendTemplatePath($this->view->getPathBasePackage('template/'));
    }


    public function testPrependTemplatePath()
    {
        $this->assertTrue($this->view->prependTemplatePath($this->view->getPathBasePackage('template/')));
    }


    /**
     * @expectedException \Exception
     */
    public function testAppendTemplatePathFail()
    {
        $this->view->appendTemplatePath('foo/bar/');
    }


    public function testGetPathTemplate()
    {
        $this->view->appendTemplatePath($this->view->getPathBasePackage('template/'));
        $this->assertEquals($this->pathBasePackage . 'template/test.php', $this->view->getPathTemplate('test'));
    }


    /**
     * @expectedException \Exception
     */
    public function testGetPathTemplateFail()
    {
        $this->view->appendTemplatePath($this->view->getPathBasePackage('template/'));
        $this->view->getPathTemplate('foo/bar');
    }


    public function testGetTemplate()
    {
        $this->view->appendTemplatePath($this->view->getPathBasePackage('template/'));
        $this->assertEquals('<p>test</p>', $this->view->getTemplate('test'));
    }


    public function testAppendAsset()
    {
        $this->view->appendAsset('mst', 'foo/bar');
        $data = $this->view->data;
        $this->assertEquals($data['assetMst'][0], 'foo/bar');
    }


    /**
     * @expectedException \Exception
     */
    public function testAppendAssetFail()
    {
        $this->view->appendAsset('foo', 'foo/bar');
    }
}

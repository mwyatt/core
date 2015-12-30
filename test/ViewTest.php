<?php

namespace Mwyatt\Core;

class ViewTest extends \PHPUnit_Framework_TestCase
{



    public function __construct()
    {
        
    }


    public function testConstruct() {
        $view = new \Mwyatt\Core\View(new \Mwyatt\Core\Url);
        $this->assertTrue($view->url);
    }


    public function testPathProject() {
        $view = new \Mwyatt\Core\View;
        $view->setPathProject('foo/bar/');
        $this->assertEquals('foo/bar/append/', $view->getPath('append/'));
    }


    public function testGetPathPackage() {
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('/var/www/html/core/src/../', $view->getPathPackage());
    }


    public function testTemplatePaths() {
        $view = new \Mwyatt\Core\View;
        $view->appendTemplatePath('foo/bar/');
        $view->appendTemplatePath('foo/bar/');
        foreach ($view->templatePaths as $path) {
            $this->assertEquals('foo/bar/', $path);
        }
    }


    public function testGetTemplate() {
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('<p>test</p>', $view->getTemplate('test'));
    }


    public function testGetPathTemplate() {
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('/var/www/html/core/src/../template/test.php', $view->getPathTemplate('test'));
    }


    public function testAppendAsset() {
        $view = new \Mwyatt\Core\View;
        $view->appendAsset('mustache', 'foo/bar');
        $data = $view->getData();
        $this->assertEquals($data['mustache'][0], 'foo/bar');
    }
}

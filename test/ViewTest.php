<?php

namespace Mwyatt\Core;

class ViewTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructUrl()
    {

        // url
        $urlBase = '192.168.1.24/core/';
        $_SERVER['HTTP_HOST'] = '192.168.1.24';
        $_SERVER['REQUEST_URI'] = '/core/foo/bar/';
        $registry = \Mwyatt\Core\Registry::getInstance();
        $registry->set('url', new \Mwyatt\Core\Url($urlBase));

        $view = new \Mwyatt\Core\View;
        $this->assertInstanceOf('\\Mwyatt\\Core\\Url', $view->url);
    }


    public function testGetTemplate()
    {
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('Test', $view->getTemplate('test'));
    }


    public function testGetPath()
    {
        define('PATH_BASE', (string) (__DIR__ . '/'));
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('/var/www/html/core/test/../', $view->getPath());
    }


    public function testGetPathTemplate()
    {
        define('PATH_BASE', (string) (__DIR__ . '/'));
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('/var/www/html/core/test/../template/test.php', $view->getPathTemplate('test'));
    }


    public function testAppendAsset()
    {
        $view = new \Mwyatt\Core\View;
        $view->appendAsset('css', 'foo/bar');
        $data = $view->getData();
        $this->assertContains('foo/bar', reset($data['asset']['css']));
    }
}

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
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('/var/www/html/core/src/../', $view->getPath());
    }


    public function testGetPathTemplate()
    {
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('/var/www/html/core/src/../template/test.php', $view->getPathTemplate('test'));
    }


    public function testGetUrlAssetCacheBusted()
    {

        // url
        $urlBase = '192.168.1.24/core/';
        $_SERVER['HTTP_HOST'] = '192.168.1.24';
        $_SERVER['REQUEST_URI'] = '/core/foo/bar/';
        $url = new \Mwyatt\Core\Url($urlBase);

        // route
        $route = new \Mwyatt\Core\Entity\Route;
        $route->type = 'get';
        $route->key = 'asset/single';
        $route->path = '/asset/:path';
        $route->options = ['require' => ['path' => '.+']];
        $url->setRoutes([$route]);

        // registry
        $registry = \Mwyatt\Core\Registry::getInstance();
        $registry->set('url', $url);

        // view
        $view = new \Mwyatt\Core\View;
        $this->assertContains('asset/test.css', $view->getUrlAssetCacheBusted('test.css'));
    }


    public function testAppendAsset()
    {
        $view = new \Mwyatt\Core\View;
        $view->appendAsset('css', 'foo/bar');
        $data = $view->getData();
        $this->assertContains('foo/bar', reset($data['asset']['css']));
    }
}

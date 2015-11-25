<?php

namespace Mwyatt\Core;

class ViewTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructUrl()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/core/foo/bar/?foo=bar', 'core/');
        $view = new \Mwyatt\Core\View($url);
        $this->assertInstanceOf('\\Mwyatt\\Core\\Url', $view->url);
    }


    public function testGetTemplate()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/core/foo/bar/?foo=bar', 'core/');
        $view = new \Mwyatt\Core\View($url);
        $this->assertEquals('Test', $view->getTemplate('test'));
    }


    public function testGetPath()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/core/foo/bar/?foo=bar', 'core/');
        $view = new \Mwyatt\Core\View($url);
        $this->assertEquals('/var/www/html/core/src/../', $view->getPath());
    }


    public function testGetPathTemplate()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/core/foo/bar/?foo=bar', 'core/');
        $view = new \Mwyatt\Core\View($url);
        $this->assertEquals('/var/www/html/core/src/../template/test.php', $view->getPathTemplate('test'));
        $this->assertEquals('/var/www/html/core/src/../template/mst/test.mst', $view->getPathTemplate('mst/test', 'mst'));
    }


    public function testAppendAsset()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/core/foo/bar/?foo=bar', 'core/');
        $view = new \Mwyatt\Core\View($url);
        $view->appendAsset('css', 'foo/bar');
        $data = $view->getData();
        $this->assertContains('foo/bar', reset($data['asset']['css']));
    }
}

<?php

namespace Mwyatt\Core;

class UrlTest extends \PHPUnit_Framework_TestCase
{


    public $host = '192.168.1.24';


    public $path = '/core/foo/bar/?foo=bar';


    public $pathInstall = 'core/';


    public function __construct()
    {
        $this->url = new \Mwyatt\Core\Url($this->host, $this->path, $this->pathInstall);
    }


    public function testConstruct()
    {
        $this->assertEquals('foo/bar/?foo=bar', $this->url->getPath());
    }


    public function testGetPath()
    {
        $this->assertEquals('foo/bar/?foo=bar', $this->url->getPath());
        $urlAlt = new \Mwyatt\Core\Url($this->host, '/core/', $this->pathInstall);
        $this->assertEquals('', $urlAlt->getPath());
    }


    public function testGenerate()
    {
        $this->url->setRoutes(['key' => '/path/:id/']);
        $this->assertEquals('http://192.168.1.24/core/path/1/', $this->url->generate('key', ['id' => 1]));
    }


    /**
     * accepts a base path then a relative end
     * too convoluted?
     */
    public function testGenerateVersioned()
    {
        $this->assertContains('http://' . $this->host . '/' . $this->pathInstall . 'asset/test.css', $this->url->generateVersioned((string) __DIR__ . '/../', 'asset/test.css'));
    }
}

<?php

namespace Mwyatt\Core;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    public $host = '192.168.1.24';
    public $path = '/core/foo/bar/?foo=bar&so=la';
    public $pathInstall = 'core/';
    public $url;
    public $routes = [
        [
            'any', '/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testSimple',
            ['id' => 'test.simple']
        ],
        [
            'any', '/foo/:name/:id/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testParams',
            ['id' => 'test.params']
        ],
        [
            'post', '/foo/bar/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testSimple'
        ]
    ];


    public function setUp()
    {
        $container = new \Pimple\Container;
        $container['Url'] = function ($container) {
            return new \Mwyatt\Core\Url($this->host, $this->path, $this->pathInstall);
        };
        $this->url = $container['Url'];
        $this->url->setRoutes($this->routes);
    }


    public function testGetPath()
    {
        $this->assertEquals('foo/bar/', $this->url->getPath());
    }


    public function testGetQueryArray()
    {
        $queryArray = $this->url->getQueryArray();
        $this->assertArrayHasKey('foo', $queryArray);
        $this->assertArrayHasKey('so', $queryArray);
        $this->assertEquals('bar', $queryArray['foo']);
        $this->assertEquals('la', $queryArray['so']);
    }


    public function testGenerate()
    {
        $this->assertEquals('http://192.168.1.24/core/foo/bar/1/', $this->url->generate('test.params', ['name' => 'bar', 'id' => 1]));
        $this->assertEquals('http://192.168.1.24/core/', $this->url->generate('test.simple'));
        $this->assertEquals('http://192.168.1.24/core/?hi=there', $this->url->generate('test.simple', [], ['hi' => 'there']));
    }


    /**
     * @expectedException \Exception
     */
    public function testGenerateException()
    {
        $this->assertEquals('http://192.168.1.24/core/foo/bar/1/', $this->url->generate('route.not.exist'));
    }


    public function testJsonSerialize()
    {
        $this->assertTrue(strlen(json_encode($this->url)) > 120);
    }


    /**
     * accepts a base path then a relative end
     * too convoluted?
     */
    public function testGenerateVersioned()
    {
        $this->assertContains('http://' . $this->host . '/' . $this->pathInstall . 'asset/test.css', $this->url->generateVersioned((string) __DIR__ . '/../', 'asset/test.css'));
    }


    /**
     * @expectedException \Exception
     */
    public function testGenerateVersionedException()
    {
        $this->url->generateVersioned((string) __DIR__ . '/../', 'asset/bad-url.css');
    }
}

<?php

namespace Mwyatt\Core;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public $controller;


    public function setUp()
    {
        $container = new \Pimple\Container;
        $container['DefaultTemplateDirectory'] = function ($container) {
            return (string) __DIR__ . '/../' . 'template/';
        };
        $container['ConfigLocal'] = function ($container) {
            return include (string) (__DIR__ . '/../') . 'config.php';
        };
        $container['Database'] = function ($container) {
            $config = $container['ConfigLocal'];
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect($config);
            return $database;
        };
        $container['ModelFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Model;
        };
        $container['MapperFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Mapper($container['Database'], $container['ModelFactory']);
        };
        $container['RepositoryFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Repository($container['MapperFactory']);
        };
        $container['ViewFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\View($container['DefaultTemplateDirectory']);
        };
        $this->controller = new \Mwyatt\Core\Controller\Test($container, new \Mwyatt\Core\View($container['DefaultTemplateDirectory']));
    }


    public function testResponse()
    {
        $response = $this->controller->response();
        $this->assertInstanceOf('\Mwyatt\Core\ResponseInterface', $response);
    }


    public function testGetService()
    {
        $config = $this->controller->getService('ConfigLocal');
        $this->assertTrue(is_array($config));
    }


    public function testGetRepository()
    {
        $repository = $this->controller->getRepository('User');
        $this->assertTrue(get_class($repository) === 'Mwyatt\Core\Repository\User');
    }


    public function testGetView()
    {
        $view = $this->controller->getView('User');
        $this->assertTrue(get_class($view) === 'Mwyatt\Core\View\User');
    }


    /**
     * @expectedException \Exception
     */
    public function testRedirectFail()
    {
        $this->controller->redirect('does-not-exist');
    }
}

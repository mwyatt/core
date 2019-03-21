<?php

namespace Mwyatt\Core;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;


    public function setUp()
    {
        $container = new \Pimple\Container;
        $container['ProjectPath'] = (string) (__DIR__ . '/../');
        $container['Database'] = function ($container) {
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect();
            $database->exec(file_get_contents($container['ProjectPath'] . 'definition.sql'));
            $database->exec(file_get_contents($container['ProjectPath'] . 'test-data.sql'));
            return $database;
        };
        $container['ModelFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Model;
        };
        $container['IteratorFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Iterator;
        };
        $container['MapperFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Mapper(
                ['Database' => $container['Database']],
                $container['ModelFactory'],
                $container['IteratorFactory']
            );
        };
        $container['User'] = function ($container) {
            return new \Mwyatt\Core\Service\User($container);
        };
        $container['View'] = function ($container) {
            return new \Mwyatt\Core\View((string) __DIR__ . '/../' . 'template/');
        };
        $this->controller = new \Mwyatt\Core\Controller\Test($container, $container['View']);
    }


    public function testGetMapper()
    {
        $userService = $this->controller->getService('User');
        $userMapper = $userService->getMapper('User');
        $this->assertTrue(get_class($userMapper) === 'Mwyatt\Core\Mapper\User');
    }
}

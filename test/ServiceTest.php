<?php

namespace Mwyatt\Core;

class ServiceTest extends \PHPUnit_Framework_TestCase
{


    public $container;


    public $controller;


    public function setUp()
    {
        $container = new \Pimple\Container;

        $container['Database'] = function ($container) {
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect(['host' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
            return $database;
        };

        $container['ModelFactory'] = function ($container) {
            return new \Mwyatt\Core\ModelFactory;
        };

        $container['MapperFactory'] = function ($container) {
            return new \Mwyatt\Core\MapperFactory($container['Database'], $container['ModelFactory']);
        };

        $container['User'] = function ($container) {
            return new \Mwyatt\Core\Service\User($container['MapperFactory'], $container['ModelFactory']);
        };

        $this->controller = new \Mwyatt\Core\Controller($container, new \Mwyatt\Core\View);
    }


    public function testInsert()
    {
        $user = $this->controller->get('User');
        $userModel = $user->insert([
            'email' => 'martin.wyatt@gmail.com',
            'password' => md5('123123123'),
            'timeRegistered' => time(),
            'nameFirst' => 'Martin',
            'nameLast' => 'Wyatt'
        ]);
        $this->assertGreaterThan(0, $userModel->id);
    }


    public function testSelect($value = '')
    {
        
    }
}

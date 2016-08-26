<?php

namespace Mwyatt\Core;

class ServiceAssocTest extends \PHPUnit_Framework_TestCase
{


    public $container;


    public $controller;


    public $exampleUserData = [
        'email' => 'martin.wyatt@gmail.com',
        'password' => '123123123',
        'timeRegistered' => 129038190382392,
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt'
    ];


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
        $serviceUser = $this->controller->get('User');
        $modelUser = $serviceUser->insert($this->exampleUserData);
        $this->assertGreaterThan(0, $modelUser->get('id'));
    }


    public function testSelect()
    {
        $serviceUser = $this->controller->get('User');
        $users = $serviceUser->findAll();

        $this->assertGreaterThan(0, $users->count());
    }


    public function testUpdate()
    {
        $serviceUser = $this->controller->get('User');
        $modelUser = $serviceUser->insert($this->exampleUserData);
        $modelUser = $serviceUser->findById($modelUser->get('id'));

        $this->assertInstanceOf('Mwyatt\\Core\\Model\\User', $modelUser);
    }


    public function testDelete()
    {
        $serviceUser = $this->controller->get('User');
        $modelUsers = $serviceUser->findAll();

        foreach ($modelUsers as $modelUser) {
            $this->assertGreaterThan(0, $serviceUser->deleteById($modelUser->get('id')));
        }
    }
}

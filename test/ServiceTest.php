<?php

namespace Mwyatt\Core;

class ServiceTest extends \PHPUnit_Framework_TestCase
{


    public $container;


    public $controller;


    public $exampleUserData = [
        'email' => 'martin.wyatt@gmail.com',
        'password' => '123123123',
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt'
    ];


    public function setUp()
    {
        $container = new \Pimple\Container;

        $container['Database'] = function ($container) {
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect(['host' => '', 'basename' => 'core_1', 'username' => 'root', 'password' => '123']);
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


    /**
     * @expectedException \Exception
     */
    public function testException()
    {
        $userService = $this->controller->get('User');
        
    }


    public function testPersistInsert()
    {
        $userService = $this->controller->get('User');
        $user = $userService->register($this->exampleUserData);

        $this->assertTrue($user->get('id') > 0);
    }


    public function testFindAll()
    {
        $userService = $this->controller->get('User');
        $users = $userService->findAll();

        $this->assertTrue($users->count() > 0);
    }


    public function testPersistUpdate()
    {
        $newName = 'Bart';
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        $user = $users->current();
        $user->setNameFirst($newName);
        $userService->update($user);
        $user = $userService->findById($user->get('id'));

        $this->assertTrue($user->get('nameFirst') === $newName);
    }


    public function testPersistLog()
    {
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        $user = $users->current();

        $userLog = $userService->insertLog([
            'userId' => $user->get('id'),
            'content' => 'Example logging content 1.'
        ]);

        $this->assertTrue($userLog->get('id') > 0);
    }


    public function testFindLog()
    {
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        $userService->findLogs($users);

        foreach ($users as $user) {
            $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testDelete()
    {
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        $userService->findLogs($users);

        foreach ($users as $user) {
            $this->assertTrue($userService->delete($user) > 0);
        }
    }
}

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

        $this->controller = new \Mwyatt\Core\Controller\Test($container, new \Mwyatt\Core\View);
    }


    public function testInsert()
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


    public function testUpdateAndFindById()
    {
        $newName = 'Bart';
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        $user = $users->current();
        $user->setNameFirst($newName);
        $userService->update($user);
        $user = $userService->findById($user->get('id'));
        $this->assertTrue(is_object($user));
        $this->assertTrue($user->get('nameFirst') === $newName);
    }


    public function testInsertLog()
    {
        $userService = $this->controller->get('User');
        $userLogData = [
            'userId' => '',
            'contentId' => ''
        ];
        $users = $userService->findAll();
        foreach ($users as $user) {
            $userService->insertLog(['userId' => $user->get('id'), 'content' => 'Content for log for user ' . $user->get('nameFirst')]);
        }
        $users = $userService->findAll();
        $userService->findLogs($users);
        foreach ($users as $user) {
            foreach ($user->logs as $userLog) {
                $this->assertTrue($userLog->get('id') > 0);
            }
        }
    }


    public function testFindLog()
    {
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        $userService->findLogs($users);
        foreach ($users as $user) {
            // $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testDelete()
    {
        $userService = $this->controller->get('User');
        $users = $userService->findAll();
        foreach ($users as $user) {
            $userService->delete($user);
        }
    }
}

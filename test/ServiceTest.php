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
        $user = $userService->findById('saoidj');
    }


    public function testPersistInsert()
    {
        $userService = $this->controller->get('User');
        $userData = $this->exampleUserData;
        $user = $userService->getModel();

        try {        
            $user->setEmail($userData['email']);
            $user->setNameFirst($userData['nameFirst']);
            $user->setNameLast($userData['nameLast']);
            $user->setPassword($userData['password']);
            $userService->register($user);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->assertTrue($user->get('id') > 0);
    }


    public function testFindAll()
    {
        $userService = $this->controller->get('User');

        try {
            $users = $userService->findAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->assertTrue($users->count() > 0);
    }


    public function testPersistUpdate()
    {
        $newName = 'Bart';
        $userService = $this->controller->get('User');

        try {
            $users = $userService->findAll();
            $user = $users->current();
            $user->setNameFirst($newName);
            $userService->update($user);
            $user = $userService->findById($user->get('id'));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->assertTrue($user->get('nameFirst') === $newName);
    }


    public function testPersistLog()
    {
        $userService = $this->controller->get('User');
        $userLogService = $this->getService('User\Log');

        try {
            $users = $userService->findAll();
            $user = $users->current();
            $userLog = $userLogService->getModel();
            $userLog->setUserId($user->get('id'));
            $userLog->setContent('Example logging content 1.');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->assertTrue($userLog->get('id') > 0);
    }


    public function testFindLog()
    {
        $userService = $this->controller->get('User');

        try {
            $users = $userService->findAll();
            $userService->findLogs($users);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        foreach ($users as $user) {
            $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testDelete()
    {
        $userService = $this->controller->get('User');

        try {
            $users = $userService->findAll();
            foreach ($users as $user) {
                $this->assertTrue($userService->delete($user) > 0);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

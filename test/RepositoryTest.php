<?php

namespace Mwyatt\Core;

class RepositoryTest extends \PHPUnit_Framework_TestCase
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
            return new \Mwyatt\Core\Factory\Model;
        };

        $container['MapperFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Mapper($container['Database'], $container['ModelFactory']);
        };

        $container['RepositoryFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Repository($container['MapperFactory']);
        };

        $this->controller = new \Mwyatt\Core\Controller\Test($container, new \Mwyatt\Core\View);
    }


    public function testInsert()
    {
        $userRepo = $this->controller->getRepository('User');
        $user = $userRepo->register($this->exampleUserData);
        $this->assertTrue($user->get('id') > 0);
    }


    public function testFindAll()
    {
        $userRepo = $this->controller->getRepository('User');
        $users = $userRepo->findAll();
        $this->assertTrue($users->count() > 0);
    }


    public function testUpdateAndFindById()
    {
        $newName = 'Bart';
        $userRepo = $this->controller->getRepository('User');
        $users = $userRepo->findAll();
        $user = $users->current();
        $user->setNameFirst($newName);
        $userRepo->update($user);
        $user = $userRepo->findById($user->get('id'));
        $this->assertTrue(is_object($user));
        $this->assertTrue($user->get('nameFirst') === $newName);
    }


    public function testInsertLog()
    {
        $userRepo = $this->controller->getRepository('User');
        $userLogData = [
            'userId' => '',
            'contentId' => ''
        ];
        $users = $userRepo->findAll();
        foreach ($users as $user) {
            $log = $userRepo->insertLog(['userId' => $user->get('id'), 'content' => 'Content for log for user ' . $user->get('nameFirst')]);
        }
        $users = $userRepo->findAll();
        $userRepo->findLogs($users);
        foreach ($users as $user) {
            foreach ($user->logs as $userLog) {
                $this->assertTrue($userLog->get('id') > 0);
            }
        }
    }


    public function testFindLog()
    {
        $userRepo = $this->controller->getRepository('User');
        $users = $userRepo->findAll();
        $userRepo->findLogs($users);
        foreach ($users as $user) {
            $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testDelete()
    {
        $userRepo = $this->controller->getRepository('User');
        $users = $userRepo->findAll();
        foreach ($users as $user) {
            $userRepo->delete($user);
        }
    }
}

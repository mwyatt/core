<?php

namespace Mwyatt\Core;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $controller;
    protected $userModelData = [
        'email' => 'martin.wyatt@gmail.com',
        'password' => '123123123',
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt'
    ];


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
                $container,
                $container['ModelFactory'],
                $container['IteratorFactory']
            );
        };
        $container['User'] = function ($container) {
            return new \Mwyatt\Core\Service\User(
                $container['MapperFactory']
            );
        };
        $container['View'] = function ($container) {
            return new \Mwyatt\Core\View((string) __DIR__ . '/../' . 'template/');
        };
        $this->controller = new \Mwyatt\Core\Controller\Test($container, $container['View']);
    }


    public function testInsert()
    {
        $userService = $this->controller->getService('User');
        $database = $this->controller->getService('Database');
        $database->beginTransaction();

        try {
            $user = $userService->register(
                $this->userModelData['email'],
                $this->userModelData['password']
            );
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            exit('testInsert ' . $e->getMessage());
        }
        $this->assertTrue($user->get('id') > 0);
    }


    public function testFind()
    {
        $userService = $this->controller->getService('User');
        $userMapper = $this->controller->getMapper('User');

        $users = $userMapper->findAll();
        
        $usersCountPrimary = $users->count();
        $this->assertTrue($users->count() > 0);

        $user = $users->current();
        $users = $userMapper->findByIds($users->getIds());
        $this->assertTrue($users->count() === $usersCountPrimary);

        $user = $users->current();
        $userSingle = $userMapper->findByIds([$user->get('id')])->getFirst();
        $this->assertTrue($user->get('id') === $userSingle->get('id'));
    }


    public function testUpdate()
    {
        $userService = $this->controller->getService('User');
        $userMapper = $this->controller->getMapper('User');

        $database = $this->controller->getService('Database');
        $database->beginTransaction();

        $users = $userMapper->findAll();
        $user = $users->current();
        $newUserNameFirst = $user->get('nameFirst') . 'append';
        try {
            foreach ($users as $user) {
                $user->setNameFirst($newUserNameFirst);
                $rowCount = $userMapper->persist($user);
                $this->assertTrue($rowCount === 1);
            }
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            exit('testUpdate ' . $e->getMessage());
        }

        $user = $userMapper->findByIds([$user->get('id')])->getFirst();
        $this->assertTrue($user->get('nameFirst') === $newUserNameFirst);
    }


    /**
     * example usage of the transaction here
     * where else could this be placed really?
     */
    public function testInsertLog()
    {
        $database = $this->controller->getService('Database');
        $userService = $this->controller->getService('User');
        $userMapper = $this->controller->getMapper('User');

        $users = $userMapper->findAll();
        try {
            $database->beginTransaction();
            foreach ($users as $user) {
                $log = $userService->insertLog(['userId' => $user->get('id'), 'content' => 'Content for log for user ' . $user->get('nameFirst')]);
            }
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            exit('testInsertLog ' . $e->getMessage());
        }
        $users = $userMapper->findAll();
        $userService->findLogs($users);
        foreach ($users as $user) {
            foreach ($user->logs as $userLog) {
                $this->assertTrue($userLog->get('id') > 0);
            }
        }
        
        // testFindLog
        $userService = $this->controller->getService('User');
        $users = $userMapper->findAll();
        $userService->findLogs($users);
        foreach ($users as $user) {
            $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testDelete()
    {
        $database = $this->controller->getService('Database');
        $userService = $this->controller->getService('User');
        $userMapper = $this->controller->getMapper('User');

        try {
            $database->beginTransaction();
            $users = $userMapper->findAll();
            $userService->delete($users);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            exit('testDelete ' . $e->getMessage());
        }
    }
}

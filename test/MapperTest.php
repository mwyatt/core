<?php

namespace Mwyatt\Core;

class MapperTest extends \PHPUnit_Framework_TestCase
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
            $user = $userService->register($this->userModelData['email'], $this->userModelData['password']);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            $this->assertTrue(0);
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
        $rowCount = 0;
        $userService = $this->controller->getService('User');
        $userMapper = $this->controller->getMapper('User');
        $database = $this->controller->getService('Database');
        $database->beginTransaction();

        $users = $userMapper->findAll();
        $user = $users->getFirst();
        $newUserNameFirst = $user->get('nameFirst') . 'append';
        try {
            $user->setNameFirst($newUserNameFirst);
            foreach ($users as $user) {
                $rowCount += $userMapper->persist($user);
            }
            $this->assertTrue($rowCount > 0);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            $this->assertTrue(0);
        }

        $users = $userMapper->findAll();
        $user = $users->getFirst();
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
            $this->assertTrue(0);
        }
        $users = $userMapper->findAll();
        $userService->findLogs($users);
        foreach ($users as $user) {
            foreach ($user->logs as $userLog) {
                $this->assertTrue($userLog->get('id') > 0);
            }
        }

        // testFindLog
        $users = $userMapper->findAll();
        $userService->findLogs($users);
        foreach ($users as $user) {
            $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testFindByColValues()
    {
        $database = $this->controller->getService('Database');
        $userService = $this->controller->getService('User');
        $userMapper = $this->controller->getMapper('User');
        $nameFirst = 'Billy';

        $users = $userMapper->findByColValues('nameFirst', [$nameFirst]);
        $this->assertTrue($users->count() > 0);
        $user = $users->getFirst();
        $this->assertTrue($user->nameFirst === $nameFirst);
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
            $this->assertTrue(0);
        }
    }
}

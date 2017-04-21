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
                $container['Database'],
                $container['ModelFactory'],
                $container['IteratorFactory']
            );
        };
        $container['RepositoryFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Repository($container['MapperFactory']);
        };
        $container['View'] = function ($container) {
            return new \Mwyatt\Core\View((string) __DIR__ . '/../' . 'template/');
        };
        $this->controller = new \Mwyatt\Core\Controller\Test($container, $container['View']);
    }


    public function testInsert()
    {
        $userRepo = $this->controller->getRepository('User');
        $database = $this->controller->getService('Database');
        $database->beginTransaction();

        try {
            $user = $userRepo->register($this->userModelData['email'], $this->userModelData['password']);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            $this->assertTrue(0);
        }

        $this->assertTrue($user->get('id') > 0);
    }


    public function testFind()
    {
        $userRepo = $this->controller->getRepository('User');
        $users = $userRepo->findAll();
        $usersCountPrimary = $users->count();
        $this->assertTrue($users->count() > 0);

        $user = $users->current();
        $users = $userRepo->findByIds($users->getIds());
        $this->assertTrue($users->count() === $usersCountPrimary);

        $user = $users->current();
        $userSingle = $userRepo->findById($user->get('id'));
        $this->assertTrue($user->get('id') === $userSingle->get('id'));
    }


    public function testUpdate()
    {
        $rowCount = 0;
        $userRepo = $this->controller->getRepository('User');
        $database = $this->controller->getService('Database');
        $database->beginTransaction();

        $users = $userRepo->findAll();
        $user = $users->getFirst();
        $newUserNameFirst = $user->get('nameFirst') . 'append';
        try {
            $user->setNameFirst($newUserNameFirst);
            foreach ($users as $user) {
                $rowCount += $userRepo->persist($user);
            }
            $this->assertTrue($rowCount > 0);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            $this->assertTrue(0);
        }

        $users = $userRepo->findAll();
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
        $userRepo = $this->controller->getRepository('User');
        $users = $userRepo->findAll();
        try {
            $database->beginTransaction();
            foreach ($users as $user) {
                $log = $userRepo->insertLog(['userId' => $user->get('id'), 'content' => 'Content for log for user ' . $user->get('nameFirst')]);
            }
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            $this->assertTrue(0);
        }
        $users = $userRepo->findAll();
        $userRepo->findLogs($users);
        foreach ($users as $user) {
            foreach ($user->logs as $userLog) {
                $this->assertTrue($userLog->get('id') > 0);
            }
        }

        // testFindLog
        $users = $userRepo->findAll();
        $userRepo->findLogs($users);
        foreach ($users as $user) {
            $this->assertTrue($user->logs->count() > 0);
        }
    }


    public function testFindByColValues()
    {
        $database = $this->controller->getService('Database');
        $userRepo = $this->controller->getRepository('User');
        $nameFirst = 'Billy';

        $users = $userRepo->findByColValues('nameFirst', [$nameFirst]);
        $this->assertTrue($users->count() > 0);
        $user = $users->getFirst();
        $this->assertTrue($user->nameFirst === $nameFirst);
    }


    public function testDelete()
    {
        $database = $this->controller->getService('Database');
        $userRepo = $this->controller->getRepository('User');

        try {
            $database->beginTransaction();
            $users = $userRepo->findAll();
            $userRepo->delete($users);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();
            $this->assertTrue(0);
        }
    }
}

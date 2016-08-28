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


    public function testInsert()
    {
        $serviceUser = $this->controller->get('User');
        $user = $serviceUser->insert($this->exampleUserData);

        $this->assertGreaterThan(0, $user->get('id'));
    }


    public function testInsertLog()
    {
        $serviceUser = $this->controller->get('User');
        $users = $serviceUser->findAll();
        $user = $users->current();
echo '<pre>';
print_r($user);
echo '</pre>';
exit;

        $serviceUser->insertLog([
            'userId' => $user->get('id'),
            'content' => 'Example logging content.'
        ]);

        $serviceUser->insertLog([
            'userId' => $user->get('id'),
            'content' => 'Example logging content.'
        ]);

        $serviceUser->insertLog([
            'userId' => $user->get('id'),
            'content' => 'Example logging content.'
        ]);
    }


    public function testFind()
    {
        $serviceUser = $this->controller->get('User');
        $users = $serviceUser->findAll();

        $this->assertGreaterThan(0, $users->count());
    }


    public function testFindLog()
    {
        $serviceUser = $this->controller->get('User');
        $serviceUser->findAll();
        $serviceUser->findLogs();

        echo '<pre>';
        print_r($serviceUser);
        echo '</pre>';
        exit;
        

        // $this->assertGreaterThan(0, $users->count());
    }


    // public function testUpdate()
    // {
    //     $serviceUser = $this->controller->get('User');
    //     $modelUser = $serviceUser->insert($his->$this->exampleUserData);
    //     $modelUser = $serviceUser->findById($modelUser->get('id'));

    //     $this->assertInstanceOf('Mwyatt\\Core\\Model\\User', $modelUser);
    // }


    // public function testDelete()
    // {
    //     $serviceUser = $this->controller->get('User');
    //     $modelUsers = $serviceUser->findAll();

    //     foreach ($modelUsers as $modelUser) {
    //         $this->assertGreaterThan(0, $serviceUser->deleteById($modelUser->get('id')));
    //     }
    // }
}

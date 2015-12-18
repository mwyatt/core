<?php

namespace Mwyatt\Core;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{


    public $container;


    public function setUp()
    {
        $this->container = new \Pimple\Container;
        $this->container['path.base'] = (string) __DIR__ . '/../';
        $this->container['database'] = function($container) {
            return new \Mwyatt\Core\Database\Pdo(include $container['path.base'] . 'config.php');
        };
    }


    public function testConnectPdo()
    {
        $database = $this->container['database'];
        $database->connect();
    }


    public function testDisconnect()
    {
        $database = $this->container['database'];
        $database->connect();
        $database->disconnect();
    }


    /**
     * @expectedException \PDOException
     */
    public function testGetStatement()
    {
        $database = $this->container['database'];
        $database->getStatement();
    }


    public function testPrepare()
    {
        $database = $this->container['database'];
        $database->prepare('select * from test');
        $database->getStatement();
    }


    /**
     * @expectedException \Exception
     */
    public function testConnectFailPdo()
    {
        $this->container['database'] = function($container) {
            $credentials = include $container['path.base'] . 'config.php';
            $credentials['database.password'] = null;
            return new \Mwyatt\Core\Database\Pdo($credentials);
        };
        $database = $this->container['database'];
        $database->connect();
    }

}

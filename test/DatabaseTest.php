<?php

namespace Mwyatt\Core;


/**
 * currently reliant on there being an actual database
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase
{


    public $container;


    public function setUp()
    {
        $this->container = new \Pimple\Container;
        $this->container['path.base'] = (string) __DIR__ . '/../';
        $this->container['database'] = function ($container) {
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
        $database->prepare('select * from person');
    }


    public function testExecute()
    {
        $database = $this->container['database'];
        $database->prepare('select * from person');
        $database->execute();
    }


    public function testInsert()
    {
        $database = $this->container['database'];
        $data = [
            ['name' => 'Foo Bar', 'telephoneLandline' => '2147483647'],
            ['name' => 'David Smith', 'telephoneLandline' => '239473209'],
            ['name' => 'Peter Parker', 'telephoneLandline' => '91237298371']
        ];
        foreach ($data as $row) {
            $insertId = $database->insert('person', $row);
            $this->assertGreaterThan(0, $insertId);
        }
    }


    /**
     * @expectedException \Exception
     */
    public function testConnectFailPdo()
    {
        $this->container['database'] = function ($container) {
            $credentials = include $container['path.base'] . 'config.php';
            $credentials['database.password'] = null;
            return new \Mwyatt\Core\Database\Pdo($credentials);
        };
        $database = $this->container['database'];
        $database->connect();
    }


    public function testSelect()
    {
        $database = $this->container['database'];
        $database->select('person', ['name' => 'Foo Bar']);
        foreach ($database->fetchAll() as $row) {
            $this->assertEquals($row['telephoneLandline'], '2147483647');
        }
    }


    public function testUpdate()
    {
        $database = $this->container['database'];
        $database->update('person', ['telephoneLandline' => '123'], 'telephoneLandline > 0');
        $database->select('person');
        foreach ($database->fetchAll() as $row) {
            $this->assertEquals($row['telephoneLandline'], '123');
        }
    }


    public function testDelete()
    {
        $database = $this->container['database'];
        $affected = $database->delete('person', 'id > 0');
        $this->assertGreaterThan(2, $affected);
    }
}

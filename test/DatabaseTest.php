<?php

namespace Mwyatt\Core;

/**
 * the database must handle the following and return set data
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase
{


    public $database;


    public function setUp()
    {
        $this->database = new \Mwyatt\Core\Database\Pdo;
        $this->database->connect(['host' => '', 'basename' => 'core_1', 'username' => 'root', 'password' => '123']);
    }


    /**
     * @expectedException \Exception
     */
    public function testConnectFail()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'basename' => '', 'username' => '', 'password' => '']);
    }


    public function testPrepareExecuteRowCount()
    {
        $this->assertInstanceOf('\PDOStatement', $this->database->prepare("insert into user (email, password, timeRegistered, nameFirst, nameLast) values ('martin.wyatt@gmail.com', '123123123', '123123123', 'Martin', 'Wyatt');"));

        $this->assertTrue($this->database->execute());

        $this->assertEquals(1, $this->database->getRowCount());

        $this->assertGreaterThan(0, $this->database->getLastInsertId());
    }


    public function testFetch()
    {
        $this->database->prepare('select * from user;');
        $this->database->execute();
        $this->assertTrue(\Mwyatt\Core\Helper::arrayKeyExists(['id', 'email', 'password', 'timeRegistered', 'nameFirst', 'nameLast'], $this->database->fetch()));

        $this->database->prepare('select * from user;');
        $this->database->execute();
        $this->assertTrue(count($this->database->fetchAll()) >= 1);
    }


    public function testUpdate()
    {
        $this->database->prepare('select * from user;');
        $this->database->execute();
        $user = $this->database->fetch();

        $this->database->prepare("update user set nameFirst = 'Dr Doom' where id = " . $user['id']);
        $this->database->execute();

        $this->database->prepare('select * from user where id = ' . $user['id']);
        $this->database->execute();
        $user = $this->database->fetch();
                
        $this->assertEquals('Dr Doom', $user['nameFirst']);
        $this->assertEquals($this->database->getRowCount(), 1);
    }


    public function testDelete()
    {
        $this->database->prepare("delete from user");
        $this->database->execute();
        $this->assertEquals($this->database->getRowCount(), 1);
    }


    public function testDisconnect()
    {
        $this->assertTrue($this->database->disconnect());
    }
}

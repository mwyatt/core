<?php

namespace Mwyatt\Core;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    private $database;


    public function setUp()
    {
        $this->database = new \Mwyatt\Core\Database\Pdo;
        $this->database->connect();
        $basePath = (string) __DIR__ . '/../';
        $this->database->exec(file_get_contents($basePath . 'definition.sql'));
        $this->database->exec(file_get_contents($basePath . 'test-data.sql'));
    }


    /**
     * @expectedException \Exception
     */
    public function testConnectFail()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(
            'fail',
            'fail',
            'fail',
            'fail'
        );
    }


    public function testPrepareExecuteRowCount()
    {
        $this->database->prepare("insert into user (email, password, timeCreated, nameFirst, nameLast) values ('joe.blogs@gmail.com', 'hash', '99999999', 'Joe', 'Blogs')");
        $this->assertTrue($this->database->execute());
        $this->assertEquals(1, $this->database->getRowCount());
        $this->assertGreaterThan(0, $this->database->getLastInsertId());
    }


    public function testFetch()
    {
        $this->database->prepare('select * from user;');
        $this->database->execute();
        $this->assertTrue(\Mwyatt\Core\Helper::arrayKeyExists(['id', 'email', 'password', 'timeCreated', 'nameFirst', 'nameLast'], $this->database->fetch()));

        $this->database->prepare('select * from user;');
        $this->database->execute();
        $this->assertTrue(count($this->database->fetchAll()) >= 1);
    }


    public function testUpdate()
    {
        $this->database->query('select * from user;');
        $user = $this->database->fetch();
        $this->database->query("update user set nameFirst = 'Dr Doom' where id = '" . $user['id'] . "'");
        $amountUpdated = $this->database->getRowCount();
        $this->database->query("select * from user where id = '" . $user['id'] . "'");
        $user = $this->database->fetch();
        $this->assertEquals('Dr Doom', $user['nameFirst']);
        $this->assertTrue($amountUpdated == 1);
    }


    public function testDelete()
    {
        $this->database->prepare("delete from user");
        $this->database->execute();
        $this->assertTrue($this->database->getRowCount() > 0);
    }


    public function testDisconnect()
    {
        $this->assertTrue($this->database->disconnect());
    }
}

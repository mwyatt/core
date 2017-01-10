<?php

namespace Mwyatt\Core;

class DatabaseTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected $pdo = null;

    
    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
       if (null === $this->pdo) {
           $this->pdo = new \PDO('sqlite::memory:');
           $tableDefinitions = include (string) __DIR__ . '/../definition.sql';
           echo '<pre>';
           print_r($tableDefinitions);
           echo '</pre>';
           exit;
           
           $this->pdo->exec('create table [tablename]([table-definition])');
       }
       return $this->createDefaultDBConnection($this->pdo, ':memory:');
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
       return $this->createXMLDataSet('[path/to/xml-seed-file]');
    }


    public function testDatabaseConnection()
    {
       $pdo = $this->getConnection()->getConnection();
       echo '<pre>';
       print_r($pdo);
       echo '</pre>';
       exit;
       
       // Do your database-tests here using the required pdo-object
    }


    public function setUp()
    {
        // $this->database = 
        $connection = $this->getConnection();
        echo '<pre>';
        print_r($connection);
        echo '</pre>';
        exit;
        
    }


    /**
     * @expectedException \Exception
     */
    public function testConnectFail()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $config = include (string) (__DIR__ . '/../') . 'config.php';
        $config['database.password'] = 'notThePassword';
        $database->connect(
            $config['database.host'],
            $config['database.basename'],
            $config['database.username'],
            $config['database.password']
        );
    }


    public function testPrepareExecuteRowCount()
    {
        $this->assertInstanceOf('\PDOStatement', $this->database->prepare("insert into user (email, password, timeCreated, nameFirst, nameLast) values ('martin.wyatt@gmail.com', '123123123', '123123123', 'Martin', 'Wyatt');"));

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

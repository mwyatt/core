<?php

namespace Mwyatt\Core;

class ModelTest extends \PHPUnit_Framework_TestCase
{


    /**
     * must be before registry filled
     * @expectedException Exception
     */
    public function testConnectFail()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => '', 'username' => '', 'password' => '']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);
    }


    /**
     * should not throw exception
     */
    public function testConnect()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);
    }


    public function testCreate()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);
        $entityTest = new \Mwyatt\Core\Entity\Test;
        $entityTest->bar = 'test';

        // multi
        $modelTest->create([$entityTest, $entityTest]);
        $this->assertCount(2, $modelTest->getData());

        // single
        $modelTest->create([$entityTest]);
        $this->assertCount(1, $modelTest->getData());
    }


    public function testRead()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);
        $modelTest->read();
        $this->assertGreaterThan(0, $modelTest->getData());
    }


    /**
     * read by known string which will exist
     * then read by known ids from results and compare count
     */
    public function testReadColumn()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);

        // string
        $modelTest->readColumn(['test'], 'bar');
        $this->assertGreaterThan(0, $modelTest->getData());

        // get ids
        $ids = [];
        foreach ($modelTest->getData() as $entityTest) {
            $ids[] = $entityTest->getId();
        }

        // int
        $modelTest->readColumn($ids);
        $this->assertCount(count($ids), $modelTest->getData());
    }


    public function testUpdate()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);
        $modelTest->read();
        $dataCount = count($modelTest->getData());
        foreach ($modelTest->getData() as $entityTest) {
            $entityTest->bar = 'test-updated';
        }
        $modelTest->update($modelTest->getData());
        foreach ($modelTest->getData() as $success) {
            $this->assertEquals(1, $success);
        }
        $this->assertCount($dataCount, $modelTest->getData());
    }


    public function testDelete()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']);
        $modelTest = new \Mwyatt\Core\Model\Test($database);
        $modelTest->read();
        $dataCount = count($modelTest->getData());
        $modelTest->delete($modelTest->getData());
        foreach ($modelTest->getData() as $success) {
            $this->assertEquals(1, $success);
        }
        $this->assertCount($dataCount, $modelTest->getData());
    }
}

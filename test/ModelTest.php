<?php

namespace Mwyatt\Core;

class ModelTest extends \PHPUnit_Framework_TestCase
{


    /**
     * must be before registry filled
     * @expectedException Exception
     */
    public function testConstructFail()
    {
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));
    }


    /**
     * should not throw exception
     */
    public function testConstruct()
    {
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));
    }


    public function testCreate()
    {
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));
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
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));
        $modelTest->read();
        $this->assertGreaterThan(0, $modelTest->getData());
    }


    /**
     * read by known string which will exist
     * then read by known ids from results and compare count
     */
    public function testReadColumn()
    {
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));

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
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));
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
        $modelTest = new \Mwyatt\Core\Model\Test(new \Mwyatt\Core\Database\Pdo(['host' => '', 'port' => '', 'basename' => 'phpunit_1', 'username' => 'root', 'password' => '123']));
        $modelTest->read();
        $dataCount = count($modelTest->getData());
        $modelTest->delete($modelTest->getData());
        foreach ($modelTest->getData() as $success) {
            $this->assertEquals(1, $success);
        }
        $this->assertCount($dataCount, $modelTest->getData());
    }
}

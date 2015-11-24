<?php

namespace Mwyatt\Core;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{


    public function testConnectPdo()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect([
            'host' => '',
            'port' => '',
            'basename' => 'phpunit_1',
            'username' => 'root',
            'password' => '123'
        ]);
    }


    /**
     * @expectedException Exception
     */
    public function testConnectFailPdo()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->connect([
            'host' => '',
            'port' => '',
            'basename' => 'phpunit_1',
            'username' => 'root',
            'password' => ''
        ]);
    }
}

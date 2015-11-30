<?php

namespace Mwyatt\Core;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{


    public function testConnectPdo()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $database->setCredentials(include (string) __DIR__ . '/../config.php');
        $database->connect();
    }


    /**
     * @expectedException Exception
     */
    public function testConnectFailPdo()
    {
        $database = new \Mwyatt\Core\Database\Pdo;
        $credentials = include (string) __DIR__ . '/../config.php';
        $credentials['database.password'] = null;
        $database->setCredentials($credentials);
        $database->connect();
    }
}

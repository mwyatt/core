<?php

namespace Mwyatt\Core;

class FactoryMapperTest extends \PHPUnit_Framework_TestCase
{
    public $mapperFactory;


    public function setUp()
    {
        $container = new \Pimple\Container;
        $container['ProjectPath'] = (string) (__DIR__ . '/../');
        $container['Database'] = function ($container) {
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect();
            $database->exec(file_get_contents($container['ProjectPath'] . 'definition.sql'));
            $database->exec(file_get_contents($container['ProjectPath'] . 'test-data.sql'));
            return $database;
        };
        $container['Database2'] = function ($container) {
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect();
            return $database;
        };
        $container['ModelFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Model;
        };
        $container['IteratorFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Iterator;
        };
        $container['MapperFactory'] = function ($container) {
            return new \Mwyatt\Core\Factory\Mapper(
                $container,
                $container['ModelFactory'],
                $container['IteratorFactory']
            );
        };
        $this->mapperFactory = $container['MapperFactory'];
    }


    public function testGet()
    {
        $mapper = $this->mapperFactory->get('User');
        $users = $mapper->findAll();

        $this->assertTrue(is_object($users));
        $this->assertTrue($users->count() > 0);
        $this->assertTrue(get_class($mapper) === 'Mwyatt\Core\Mapper\User');
    }


    /**
     * @expectedException \Exception
     */
    public function testGet2()
    {
        $mapper = $this->mapperFactory->get('User');

        // table `user` will not exist in this database
        $users = $mapper->findAllFromDatabase2();
    }
}

<?php

namespace Mwyatt\Core;

class IteratorModelTest extends \PHPUnit_Framework_TestCase
{
    public $container;
    public $controller;
    public $models;


    public function setUp()
    {

        $container = new \Pimple\Container;

        $container['Database'] = function ($container) {
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect(['host' => '', 'basename' => 'core_1', 'username' => 'root', 'password' => '123']);
            return $database;
        };

        $container['ModelFactory'] = function ($container) {
            return new \Mwyatt\Core\ModelFactory;
        };

        $container['MapperFactory'] = function ($container) {
            return new \Mwyatt\Core\MapperFactory($container['Database'], $container['ModelFactory']);
        };

        $container['User'] = function ($container) {
            return new \Mwyatt\Core\Service\User($container['MapperFactory'], $container['ModelFactory']);
        };

        $this->controller = new \Mwyatt\Core\Controller($container, new \Mwyatt\Core\View);

        $this->controller-
        


        $cat1 = new \stdClass;
        $cat1->name = 'Felix';
        $cat1->type = 'Tabby';
        $cat1->weight = 200;

        $cat2 = new \stdClass;
        $cat2->name = 'Jerrard';
        $cat2->type = 'Tabby';
        $cat2->weight = 125;

        $cat3 = new \stdClass;
        $cat3->name = 'Maru';
        $cat3->type = 'Scottish Fold';
        $cat3->weight = 500;

        $cat4 = new \stdClass;
        $cat4->name = 'Hannah';
        $cat4->type = 'Spooky';
        $cat4->weight = 423;

        $this->objects = [$cat1, $cat2, $cat3, $cat4];
    }


    public function testGetKeyedByProperty()
    {
        $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        $cats = $catBag->getKeyedByProperty('name');
        foreach ($cats as $key => $value) {
            $this->assertTrue(is_string($key));
        }

        $cats = $catBag->getKeyedByProperty('weight');
        foreach ($cats as $key => $value) {
            $this->assertTrue(is_numeric($key));
        }
    }


    public function testGetKeyedByPropertyMulti()
    {
        $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        $catTypeGroups = $catBag->getKeyedByPropertyMulti('type');

        foreach ($catTypeGroups as $type => $cats) {
            if ($type == 'Tabby') {
                $this->assertTrue(count($cats) == 2);
            } else {
                $this->assertTrue(count($cats) == 1);
            }
        }
    }


    public function testFilterOutByPropertyValue()
    {
        $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        $catBag->filterOutByPropertyValue('name', 'Maru');

        foreach ($catBag as $cat) {
            $this->assertTrue($cat->name !== 'Maru');
        }
    }


    public function testExtractProperty()
    {
        $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        $names = $catBag->extractProperty('name');
        
        foreach ($catBag as $cat) {
            $this->assertTrue(in_array($cat->name, $names));
        }
    }


    public function testGetByPropertyValue()
    {
        $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        $cats = $catBag->getByPropertyValue('type', ['Tabby']);
        $this->assertTrue(count($cats) == 2);
    }


    public function testSort()
    {
        $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        
        $catBag->uasort(function ($a, $b) {
            return strcasecmp($a->name, $b->name);
        });

        $orderExpected = ['Felix', 'Hannah', 'Jerrard', 'Maru'];
        foreach ($catBag as $cat) {
            $this->assertTrue(current($orderExpected) == $cat->name);
            next($orderExpected);
        }

        $catBag->uasort(function ($a, $b) {
            return $a->weight > $b->weight;
        });

        $orderExpected = [125, 200, 423, 500];
        foreach ($catBag as $cat) {
            $this->assertTrue(current($orderExpected) == $cat->weight);
            next($orderExpected);
        }

        // other examples:

        // if ($type == 'float') {
        //     $a->$property += 0;
        //     $b->$property += 0;
        // }
        // if ($type == 'string') {
        //     if ($order == 'asc') {
        //         return strcasecmp($a->$property, $b->$property);
        //     } else {
        //         return strcasecmp($b->$property, $a->$property);
        //     }
        // } elseif ($type == 'integer' || $type == 'float') {
        //     if ($order == 'asc') {
        //         if ($a->$property == $b->$property) {
        //             return 0;
        //         }
        //         return $a->$property < $b->$property ? -1 : 1;
        //     } else {
        //         if ($a->$property == $b->$property) {
        //             return 0;
        //         }
        //         return $a->$property > $b->$property ? -1 : 1;
        //     }
        // }
    }
}

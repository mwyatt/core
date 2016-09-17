<?php

namespace Mwyatt\Core;

class IteratorModelTest extends \PHPUnit_Framework_TestCase
{
    public $iterator;


    public function setUp()
    {
        $models = [];
        $datas = [
            [
                'id' => 1,
                'email' => 'example@exmple.com',
                'nameFirst' => 'Martin',
                'nameLast' => 'Wyatt',
                'timeRegistered' => time(),
                'password' => sha1('ojfdgodfig')
            ]
        ];
        foreach ($datas as $data) {
            $models[] = new \Mwyatt\Core\Model\User($data);
        }
        $this->iterator = new \Mwyatt\Core\Iterator\Model($models);
    }


    public function testGetById()
    {
        $model = $this->iterator->getById(1);
        $this->assertTrue($model->get('id') === 1);
    }


    public function testGetByPropertyValues()
    {
        $iterator = $this->iterator->getByPropertyValues('nameFirst', ['Martin', 'David']);
        $model = $iterator->current();
        $this->assertTrue($model->get('nameFirst') === 'Martin');
    }


    // public function testGetKeyedByPropertyMulti()
    // {
    //     $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
    //     $catTypeGroups = $catBag->getKeyedByPropertyMulti('type');

    //     foreach ($catTypeGroups as $type => $cats) {
    //         if ($type == 'Tabby') {
    //             $this->assertTrue(count($cats) == 2);
    //         } else {
    //             $this->assertTrue(count($cats) == 1);
    //         }
    //     }
    // }


    // public function testFilterOutByPropertyValue()
    // {
    //     $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
    //     $catBag->filterOutByPropertyValue('name', 'Maru');

    //     foreach ($catBag as $cat) {
    //         $this->assertTrue($cat->name !== 'Maru');
    //     }
    // }


    // public function testExtractProperty()
    // {
    //     $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
    //     $names = $catBag->extractProperty('name');
        
    //     foreach ($catBag as $cat) {
    //         $this->assertTrue(in_array($cat->name, $names));
    //     }
    // }


    // public function testGetByPropertyValue()
    // {
    //     $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
    //     $cats = $catBag->getByPropertyValue('type', ['Tabby']);
    //     $this->assertTrue(count($cats) == 2);
    // }


    // public function testSort()
    // {
    //     $catBag = new \Mwyatt\Core\ObjectIterator($this->objects);
        
    //     $catBag->uasort(function ($a, $b) {
    //         return strcasecmp($a->name, $b->name);
    //     });

    //     $orderExpected = ['Felix', 'Hannah', 'Jerrard', 'Maru'];
    //     foreach ($catBag as $cat) {
    //         $this->assertTrue(current($orderExpected) == $cat->name);
    //         next($orderExpected);
    //     }

    //     $catBag->uasort(function ($a, $b) {
    //         return $a->weight > $b->weight;
    //     });

    //     $orderExpected = [125, 200, 423, 500];
    //     foreach ($catBag as $cat) {
    //         $this->assertTrue(current($orderExpected) == $cat->weight);
    //         next($orderExpected);
    //     }

    //     // other examples:

    //     // if ($type == 'float') {
    //     //     $a->$property += 0;
    //     //     $b->$property += 0;
    //     // }
    //     // if ($type == 'string') {
    //     //     if ($order == 'asc') {
    //     //         return strcasecmp($a->$property, $b->$property);
    //     //     } else {
    //     //         return strcasecmp($b->$property, $a->$property);
    //     //     }
    //     // } elseif ($type == 'integer' || $type == 'float') {
    //     //     if ($order == 'asc') {
    //     //         if ($a->$property == $b->$property) {
    //     //             return 0;
    //     //         }
    //     //         return $a->$property < $b->$property ? -1 : 1;
    //     //     } else {
    //     //         if ($a->$property == $b->$property) {
    //     //             return 0;
    //     //         }
    //     //         return $a->$property > $b->$property ? -1 : 1;
    //     //     }
    //     // }
    // }
}

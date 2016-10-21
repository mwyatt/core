<?php

namespace Mwyatt\Core;

class IteratorModelTest extends \PHPUnit_Framework_TestCase
{
    public $iterator;
    public $datas = [
        [
            'id' => 1,
            'email' => 'example@exmple.com',
            'nameFirst' => 'Martin',
            'nameLast' => 'Wyatt',
            'timeCreated' => 1474113942,
            'password' => 'ojfdgodfig'
        ],
        [
            'id' => 2,
            'email' => 'example@exmple.coms',
            'nameFirst' => 'Steve',
            'nameLast' => 'Smith',
            'timeCreated' => 1474113942,
            'password' => 'ojfdgodfig'
        ]
    ];


    public function setUp()
    {
        $models = [];
        foreach ($this->datas as $data) {
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


    public function testGetKeyedByProperty()
    {
        $keyedByNameLast = $this->iterator->getKeyedByProperty('nameLast');
        $keys = array_keys($keyedByNameLast);
        $this->assertTrue($keys[0] === 'Wyatt');
        $this->assertTrue($keys[1] === 'Smith');
    }


    public function testGetKeyedByPropertyMulti()
    {
        $keyedByNameLast = $this->iterator->getKeyedByPropertyMulti('nameLast');
        $keys = array_keys($keyedByNameLast);
        $this->assertTrue($keys[0] === 'Wyatt');
        $this->assertTrue($keys[1] === 'Smith');
    }


    public function testExtractProperty()
    {
        $values = $this->iterator->extractProperty('nameFirst');
        $this->assertTrue($values[0] === 'Martin');
        $this->assertTrue($values[1] === 'Steve');
    }
}

<?php

namespace Mwyatt\Core;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    private $model;


    // also testSet
    public function setUp()
    {
        $this->model = new \Mwyatt\Core\Model\User;
        $this->model->id = 2;
        $this->model->email = ' example@exmple.coms ';
        $this->model->nameFirst = 'Steve';
        $this->model->nameLast = 'Smith';
        $this->model->password = 'ojfdgodfig';
    }


    public function testGet()
    {
        $this->assertTrue(strlen($this->model->id) > 0);
        $this->assertTrue(strlen($this->model->email) > 0);
        $this->assertTrue(strlen($this->model->nameFirst) > 0);
        $this->assertTrue(strlen($this->model->nameLast) > 0);
    }


    public function testSetMethod()
    {
        $this->assertTrue($this->model->email === 'example@exmple.coms');
    }


    public function testGetMethod()
    {
        $this->assertTrue($this->model->timeCreated == time());
    }


    public function testValidate()
    {
        $this->assertTrue(count($this->model->validate()) == 0);
        $this->model->email = 'invalid';
        $this->assertTrue(count($this->model->validate()) == 1);
    }


    public function testLegacyGet()
    {
        $this->assertTrue($this->model->get('nameFirst') === 'Steve');
    }
}

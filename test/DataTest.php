<?php

namespace Mwyatt\Core;

class DataTest extends \PHPUnit_Framework_TestCase
{


    /**
     * seems to be set throughout all tests when put here?
     */
    public function testConstants()
    {
        define('PATH_BASE', (string) (__DIR__ . '/../'));
    }


    public function testGetSet()
    {
        $data = new \Mwyatt\Core\Data;
        $data->setData('hi');
        $this->assertEquals('hi', $data->getData());
    }


    public function testGetSetKey()
    {
        $data = new \Mwyatt\Core\Data;
        $data->setDataKey('foo', 'bar');
        $this->assertEquals('bar', $data->getDataKey('foo'));
    }


    public function testGetDataFirst()
    {
        $data = new \Mwyatt\Core\Data;
        $data->setData([1, 2, 3]);
        $this->assertEquals(1, $data->getDataFirst());
    }


    public function testGetDataProperty()
    {
        $data = new \Mwyatt\Core\Data;
        $data->setData([
            (object) ['id' => 1],
            (object) ['id' => 2],
            (object) ['id' => 3]
        ]);
        $this->assertEquals([1, 2, 3], $data->getDataProperty('id'));
    }


    public function testAppendData()
    {
        $data = new \Mwyatt\Core\Data;
        $data
            ->setData([1])
            ->appendData(2)
            ->appendData(2);
        $this->assertEquals([1, 2, 2], $data->getData());
    }


    public function testLimitData()
    {
        $data = new \Mwyatt\Core\Data;
        $data
            ->setData([1, 2, 3])
            ->limitData([0, 1]);
        $this->assertEquals([1], $data->getData());
    }


    public function testKeyDataByProperty()
    {
        $data = new \Mwyatt\Core\Data;
        $data
            ->setData([
                (object) ['id' => 1],
                (object) ['id' => 2],
                (object) ['id' => 3]
            ])
            ->keyDataByProperty('id');
        $this->assertEquals([1, 2, 3], array_keys($data->getData()));
    }


    public function testSetDataKey()
    {
        $data = new \Mwyatt\Core\Data;
        $data->setDataKey('foo', 'bar');
        $this->assertEquals('bar', $data->getDataKey('foo'));
    }


    public function testMergeData()
    {
        $data = new \Mwyatt\Core\Data;
        $data
            ->setData([1])
            ->mergeData([1]);
        $this->assertEquals([1, 1], $data->getData());
    }


    public function testOrderByProperty()
    {
        $data = new \Mwyatt\Core\Data;

        // integer asc
        $data
            ->setData([
                (object) ['id' => 7],
                (object) ['id' => 4],
                (object) ['id' => 2]
            ])
            ->orderByProperty('id');
        $this->assertEquals([2, 4, 7], $data->getDataProperty('id'));

        // integer desc
        $data
            ->setData([
                (object) ['id' => 7],
                (object) ['id' => 4],
                (object) ['id' => 2]
            ])
            ->orderByProperty('id', 'desc');
        $this->assertEquals([7, 4, 2], $data->getDataProperty('id'));

        // float asc
        $data
            ->setData([
                (object) ['price' => 1.15],
                (object) ['price' => 1.32],
                (object) ['price' => 0.55],
                (object) ['price' => .25]
            ])
            ->orderByProperty('price');
        $this->assertEquals([.25, 0.55, 1.15, 1.32], $data->getDataProperty('price'));

        // string asc
        $data
            ->setData([
                (object) ['name' => 'Steve'],
                (object) ['name' => 'Fred'],
                (object) ['name' => 'Paul']
            ])
            ->orderByProperty('name');
        $this->assertEquals(['Fred', 'Paul', 'Steve'], $data->getDataProperty('name'));
    }
}

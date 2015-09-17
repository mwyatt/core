<?php

namespace Mwyatt\Core;

class HelperTest extends \PHPUnit_Framework_TestCase
{


    public function testArrayKeyExists()
    {
        $this->assertTrue(\Mwyatt\Core\Helper::arrayKeyExists(['foo', 'bar'], ['foo' => 'bar', 'bar' => 'foo']));
    }


    public function testGetRandomString()
    {
        $this->assertEquals(10, strlen(\Mwyatt\Core\Helper::getRandomString(10)));
    }


    public function testExplodeTrim()
    {
        $this->assertEquals(['one', 'two', 'three', 'four'], \Mwyatt\Core\Helper::explodeTrim(' one! two !three!   four ', '!'));
    }


    public function testSlugify()
    {
        $hi = ['foo' => 'bar', 'bar' => 'foo'];
        $this->assertEquals('one-two-three-four', \Mwyatt\Core\Helper::slugify(' one! two !three!   four ', '!'));
    }


    public function testPluralise()
    {
        $this->assertEquals('s', \Mwyatt\Core\Helper::pluralise([1, 2]));
    }


    public function testCalcAverage()
    {
        $this->assertEquals(75, \Mwyatt\Core\Helper::calcAverage(150, 200));
    }
}

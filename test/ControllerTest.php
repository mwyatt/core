<?php

namespace Mwyatt\Core;

class ControllerTest extends \PHPUnit_Framework_TestCase
{


    public function testConstruct()
    {
        $url = new \Mwyatt\Core\Url;
        $database = new \Mwyatt\Core\Database;
        $view = new \Mwyatt\Core\View;
        $this->assertEquals('unique-name-cache', $cache->getKey());
    }
}

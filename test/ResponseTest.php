<?php

namespace Mwyatt\Core;

class ResponseTest extends \PHPUnit_Framework_TestCase
{


    public function testGetContent()
    {
        $response = new \Mwyatt\Core\Response('example content');
        $this->assertEquals('example content', $response->getContent());
    }


    public function testStatusCode()
    {
        $response = new \Mwyatt\Core\Response;
        $this->assertEquals(200, $response->getStatusCode());
        $response->setStatusCode(404);
        $this->assertEquals(404, $response->getStatusCode());
    }
}

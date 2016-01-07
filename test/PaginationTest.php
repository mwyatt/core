<?php

namespace Mwyatt\Core;

class PaginationTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerate()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/foo/bar/?foo=bar&so=la');
        $pagination = new \Mwyatt\Core\Pagination($url);
        $paginationArray = $pagination->generate(2, 50);
        $this->assertArrayHasKey('previous', $paginationArray);
        $this->assertArrayHasKey('pages', $paginationArray);
        $this->assertArrayHasKey('next', $paginationArray);
    }
}

<?php

namespace Mwyatt\Core;

class PaginationTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerate()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24', '/foo/bar/?foo=bar&so=la');
        $pagination = new \Mwyatt\Core\Pagination($url, 2, 50);
        $pagination = $pagination->generate();
        $this->assertArrayHasKey('previous', $pagination);
        $this->assertArrayHasKey('pages', $pagination);
        $this->assertArrayHasKey('next', $pagination);
    }
}

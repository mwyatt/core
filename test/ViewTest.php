<?php

namespace Mwyatt\Core;

class ViewTest extends \PHPUnit_Framework_TestCase
{


    public function testConstruct()
    {
        $data = new \Mwyatt\Core\View;
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
        
    }
}

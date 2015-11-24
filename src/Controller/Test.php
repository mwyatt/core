<?php

namespace Mwyatt\Core\Controller;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Test extends \Mwyatt\Core\Controller
{


    public function testSimple()
    {
        return new \Mwyatt\Core\Response('testSimple', 200);
    }


    public function testParams($name, $id)
    {
        return new \Mwyatt\Core\Response("testParams, $name, $id", 200);
    }
}

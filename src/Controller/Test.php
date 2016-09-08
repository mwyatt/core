<?php

namespace Mwyatt\Core\Controller;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Test extends \Mwyatt\Core\AbstractController
{


    public function testSimple()
    {
        return new \Mwyatt\Core\Response('testSimpleContent', 200);
    }


    public function testParams($request)
    {
        return new \Mwyatt\Core\Response("testParamsContent, {$request->getUrlVar('name')}, {$request->getUrlVar('id')}", 500);
    }
}

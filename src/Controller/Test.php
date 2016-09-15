<?php

namespace Mwyatt\Core\Controller;

class Test extends \Mwyatt\Core\AbstractController
{


    public function testSimple()
    {
        return $this->response('testSimpleContent', 200);
    }


    public function testParams($request)
    {
        return $this->response("testParamsContent, {$request->getUrlVar('name')}, {$request->getUrlVar('id')}", 500);
    }
}

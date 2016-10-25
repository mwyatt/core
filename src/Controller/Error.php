<?php

namespace Mwyatt\Core\Controller;

class Error extends \Mwyatt\Core\AbstractController
{


    public function e404()
    {
        return $this->response('testSimpleContent', 404);
    }


    public function e500()
    {
        return $this->response('testSimpleContent', 500);
    }


    public function maintenance()
    {
        return $this->response('testSimpleContent', 503);
    }
}

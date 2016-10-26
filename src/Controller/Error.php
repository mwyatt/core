<?php

namespace Mwyatt\Core\Controller;

class Error extends \Mwyatt\Core\AbstractController
{


    public function e404()
    {
        $this->view->offsetSet('title', '404 Not Found');
        $this->view->offsetSet('description', 'Unable to find.');
        return $this->response($this->view->getTemplate('message'), 404);
    }


    public function e500()
    {
        $this->view->offsetSet('title', 'Server Error');
        $this->view->offsetSet('description', 'Bad.');
        return $this->response($this->view->getTemplate('message'), 500);
    }


    public function maintenance()
    {
        $this->view->offsetSet('title', 'We will be back soon');
        $this->view->offsetSet('description', 'Improve.');
        return $this->response($this->view->getTemplate('message'), 503);
    }
}

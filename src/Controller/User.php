<?php

namespace Mwyatt\Core\Controller;

class User extends \Mwyatt\Core\Controller
{


    public function all()
    {
        $serviceUser = $this->get('User');
        $users = $serviceUser->findAll();
        $this->view->data->offsetSet('users', $users);
        return $this->response($this->view->getTemplate('user/all'));
    }
}

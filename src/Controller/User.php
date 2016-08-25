<?php

namespace Mwyatt\Core\Controller;

class User extends \Mwyatt\Core\Controller
{


    public function all($request)
    {
        
        $serviceUser = $this->get('User');



        $this->view->data->offsetSet('users', $serviceUser->getAll());
        return $this->response($this->view->getTemplate('user/all'));
    }
}

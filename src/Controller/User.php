<?php

namespace Mwyatt\Core\Controller;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\Controller
{


    public function all()
    {
        $serviceUser = $this->get('User');
        $this->view->data->offsetSet('users', $serviceUser->getAll());
        return $this->response($this->view->getTemplate('user/all'));
    }
}

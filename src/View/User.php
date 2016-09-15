<?php

namespace Mwyatt\Core\View;

class User extends \Mwyatt\Core\View
{


    public function all()
    {
        return $this->getTemplate('person/all');
    }
}

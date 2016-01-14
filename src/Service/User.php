<?php

namespace Mwyatt\Core\Service;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\ServiceAbstract
{


    public function getAll()
    {
        $mapperUser = $this->mapperFactory->get('User');
        return $mapperUser->getAll();
    }


    public function appendActivity()
    {
    	// checks a central storage for the user objects
    	// finds the required activity and appends
    }


    public function create()
    {

    }
}

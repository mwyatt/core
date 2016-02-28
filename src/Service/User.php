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
        // finds the required activities and appends where needed
        //
    }


    /**
     * not always multi as how would you provide correct feedback
     * if one was not created? it either needs to be created or not
     * @param  array $user assoc
     * @return object       model/user
     */
    public function insert($user)
    {
        $userModel = $this->modelFactory->get('User');
        $userMapper = $this->mapperFactory->get('User');

        $userModel->setEmail($user['email']);
        $userModel->setNameFirst($user['nameFirst']);
        $userModel->setNameLast($user['nameLast']);
        $userModel->setPassword($user['password']);
        $userModel->setTimeRegistered(time());
        
        $userModel->id = $userMapper->insert($userModel);

        return $userModel;
    }


    public function findById($id)
    {
        
    }
}

<?php

namespace Mwyatt\Core\Service;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\ServiceAbstract
{


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
    public function insert(array $user)
    {
        $modelUser = $this->modelFactory->get('User');
        $mapperUser = $this->mapperFactory->get('User');

        $modelUser->setEmail($user['email']);
        $modelUser->setNameFirst($user['nameFirst']);
        $modelUser->setNameLast($user['nameLast']);
        $modelUser->setPassword($user['password']);
        $modelUser->setTimeRegistered(time());
        
        $modelUser->id = $mapperUser->insert($modelUser);

        return $modelUser;
    }


    public function findById($id)
    {
        $mapperUser = $this->mapperFactory->get('User');
        $modelUsers = $mapperUser->findColumn([$id], 'id');
        return $modelUsers->current();
    }


    public function deleteById($userId)
    {
        $mapperUser = $this->mapperFactory->get('User');
        return $mapperUser->deleteById($userId);
    }
}

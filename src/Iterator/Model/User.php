<?php

namespace Mwyatt\Core\Iterator\Model;

class User extends \Mwyatt\Core\Iterator\Model
{


    /**
     * example usage of more specific iterator
     * will tidy the controllers even further
     * @param  string $value
     * @return array        users
     */
    public function getByNameFirst($value)
    {
        $users = [];
        foreach ($this as $user) {
            if ($user->get('nameFirst')) {
                $users[] = $user;
            }
        }
        return $users;
    }
}

<?php

namespace Mwyatt\Core\Model;

class UserIterator extends \Mwyatt\Core\ModelIterator
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

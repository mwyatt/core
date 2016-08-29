<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    /**
     * is this the best way to handle insert|update?
     * will be more convinient using just one method instead of two
     * @param  \Mwyatt\Core\Model\User $user
     * @return object|string    the object or error string.
     */
    public function persist(\Mwyatt\Core\Model\User $user)
    {
        return $this->lazyPersist($user, [
            'email',
            'password',
            'timeRegistered',
            'nameFirst',
            'nameLast'
        ]);
    }
}

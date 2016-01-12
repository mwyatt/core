<?php

namespace Mwyatt\Core\Mapper;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\MapperAbstract
{
    const TABLE = 'user';
    const MODEL = '\\Mwyatt\\Core\\Model\\User';


    public function fetchActivity($users)
    {
        $userIds = $users->extractProperty('id');
        $activities = $activityMapper->fetchColumn($userIds, 'userId');
        foreach ($users as $user) {
            $user->activity = $activities->extractProperty()
        }
    }
}

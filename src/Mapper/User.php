<?php

namespace Mwyatt\Core\Mapper;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\MapperAbstract implements \Mwyatt\Core\MapperInterface
{
    const TABLE = 'user';
    const MODEL = '\\Mwyatt\\Core\\Model\\User';


    public function insert(\Mwyatt\Core\Model\User $user)
    {
        $sql = ['insert', 'into', $this::TABLE, '('];
        $sql[] = implode(', ', ['email', 'password', 'timeRegistered', 'nameFirst', 'nameLast']);
        $sql[] = ') values (';
        $sql[] = implode(', ', ['?', '?', '?', '?', '?']);
        $sql[] = ');';

        $this->database->prepare(implode(' ', $sql));
        $this->database->execute([
            $user->email,
            $user->password,
            $user->timeRegistered,
            $user->nameFirst,
            $user->nameLast
        ]);
        
        return $this->database->getLastInsertId();
    }
}

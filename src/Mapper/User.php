<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\MapperAbstract implements \Mwyatt\Core\MapperInterface
{


    public function insert(\Mwyatt\Core\Model\User $user)
    {
        $sql = ['insert', 'into', $this->table, '('];
        $sql[] = implode(', ', ['email', 'password', 'timeRegistered', 'nameFirst', 'nameLast']);
        $sql[] = ') values (';
        $sql[] = implode(', ', ['?', '?', '?', '?', '?']);
        $sql[] = ');';

        $this->database->prepare(implode(' ', $sql));
        $this->database->execute([
            $user->get('email'),
            $user->get('password'),
            time(),
            $user->get('nameFirst'),
            $user->get('nameLast')
        ]);
        $user->setId($this->database->getLastInsertId());
        return $user;
    }


    public function deleteById($id)
    {
        $sql = ['delete', 'from', $this->table, 'where id = ?'];

        $this->database->prepare(implode(' ', $sql));
        $this->database->execute([$id]);

        return $this->database->getRowCount();
    }
}

<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\MapperAbstract implements \Mwyatt\Core\MapperInterface
{


    public function insert(\Mwyatt\Core\Model\LogInterface $log)
    {
        $sql = ['insert', 'into', $this->table, '('];
        $sql[] = implode(', ', ['content', 'timeCreated']);
        $sql[] = ') values (';
        $sql[] = implode(', ', ['?', '?']);
        $sql[] = ');';
        $this->database->prepare(implode(' ', $sql));
        $this->database->execute([
            $log->get('content'),
            time()
        ]);
        return $this->database->getLastInsertId();
    }
}

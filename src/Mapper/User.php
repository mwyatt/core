<?php

namespace Mwyatt\Core\Mapper;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\MapperAbstract
{
    

    public $tableName = 'user';


    public function mapModel($result)
    {
        $user = $this->getModel('User');
        $user->nameFirst = $result['nameFirst'];
        $user->nameLast = $result['nameLast'];
        $user->emailAddress = $result['emailAddress'];
    }


    public function getAll()
    {
        $results = $this->database->findAll();
        $models = [];
        foreach ($results as $result) {
            $models[] = $this->mapModel($result);
        }
        return $models;
    }
}

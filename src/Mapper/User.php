<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{


    public function badMethod()
    {
        $sql = ['select', '*badThing', 'from', $this->table];
        $this->adapter->prepare(implode(' ', $sql));
        $this->adapter->execute();

    }


    /**
     * possible exception from this
     * @param  array  $data 
     * @return object|exception
     */
    public function create(array $data)
    {
        $this->testKeys($data, ['email', 'nameFirst', 'nameLast', 'password']);
        $model = $this->getModel();
        $model->setEmail($data['email']);
        $model->setNameFirst($data['nameFirst']);
        $model->setNameLast($data['nameLast']);
        $model->setPassword($data['password']);
        return $model;
    }


    /**
     * @param  \Mwyatt\Core\Model\User $user
     * @return bool
     */
    public function persist(\Mwyatt\Core\Model\User $model)
    {
        $rowCount = 0;
        $modelId = $model->get('id');
        $cols = [
            'email',
            'password',
            'timeRegistered',
            'nameFirst',
            'nameLast'
        ];
        if ($modelId) {
            $sql = $this->getUpdateGenericSql($cols);
        } else {
            $sql = $this->getInsertGenericSql($cols);
        }

        try {
            $this->adapter->prepare($sql);
            $this->adapter->bindParam(':email', $model->get('email'), $this->adapter->getParamStr());
            $this->adapter->bindParam(':password', $model->get('password'), $this->adapter->getParamStr());
            $this->adapter->bindParam(':timeRegistered', $model->get('timeRegistered'), $this->adapter->getParamInt());
            $this->adapter->bindParam(':nameFirst', $model->get('nameFirst'), $this->adapter->getParamStr());
            $this->adapter->bindParam(':nameLast', $model->get('nameLast'), $this->adapter->getParamStr());

            if ($modelId) {
                $this->adapter->bindParam(":id", $modelId, $this->adapter->getParamInt());
            }

            $this->adapter->execute();

            if ($modelId) {
                $rowCount = $this->adapter->getRowCount();
            } else {
                $model->setId($this->adapter->getLastInsertId());
                $rowCount = 1;
            }
        } catch (\Exception $e) {
            
            // 
        }

        return $rowCount;
    }


    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $sql = ['delete', 'from', $this->table, 'where id = ?'];
        $this->adapter->prepare(implode(' ', $sql));
        $this->adapter->bindParam(1, $user->get('id'), $this->adapter->getParamInt());
        $this->adapter->execute();
        return $this->adapter->getRowCount();
    }
}

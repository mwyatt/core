<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{


    /**
     * should there be an entry point where the model can be created
     * at all times?
     * @param  array  $data col > value
     * @return object       model
     */
    public function createModel(array $data)
    {

        // function for this?
        $keys = ['email', 'nameFirst', 'nameLast', 'password'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \Exception("Missing data key '$key' when creating user model.");
            }
        }

        $user = $this->getModel(isset($data['id']) ? $data['id'] : 0);
        $user->setEmail($data['email']);
        $user->setNameFirst($data['nameFirst']);
        $user->setNameLast($data['nameLast']);
        $user->setPassword($data['password']);
        $user->logs = $this->getIterator([], 'Log');

        return $user;
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
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }

        return $rowCount;
    }


    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $sql = ['delete', 'from', $this->table, 'where id = ?'];

        try {
            $this->adapter->prepare(implode(' ', $sql));
            $this->adapter->bindParam(1, $user->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            return $this->adapter->getRowCount();
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }
}

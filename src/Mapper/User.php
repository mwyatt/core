<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{


    /**
     * not all models will require this
     */
    private function validateModel(\Mwyatt\Core\AbstractModel $model)
    {
        $errors = [];
        if (strlen($model->get('password')) < 1) {
            $errors[] = 'Must have a password.';
        } elseif (strlen($model->get('email')) < 1) {
            $errors[] = 'Email must be filled.';
        }
        if ($errors) {
            throw new \Exception('User validation errors: ' . implode(' ', $errors));
        }
    }


    /**
     * this avoids a lot of the duplication which appeared in
     * the insert/update combo
     * is there always a rowCount?
     * @param  \Mwyatt\Core\AbstractModel $model 
     * @return bool                            
     */
    public function persist(\Mwyatt\Core\Model\User $model)
    {
        $this->validateModel($model);
        $isUpdate = $model->get('id');
        $method = $isUpdate ? 'getUpdateGenericSql' : 'getInsertGenericSql';
        $cols = [
            $this->adapter->getParamStr() => 'email',
            $this->adapter->getParamStr() => 'password',
            $this->adapter->getParamInt() => 'timeRegistered',
            $this->adapter->getParamStr() => 'nameFirst',
            $this->adapter->getParamStr() => 'nameLast'
        ];
        $this->adapter->prepare($this->$method($cols));
        foreach ($cols as $type => $col) {
            $this->adapter->bindParam(":$col", $model->get($col), $type);
        }
        if ($isUpdate) {
            $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
        }
        $this->adapter->execute();
        if (!$isUpdate) {
            $model->setId($this->adapter->getLastInsertId());
        }
        return $this->adapter->getRowCount();
    }
}

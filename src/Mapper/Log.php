<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\AbstractMapper
{


    public function insert(array $data)
    {
        $data['timeCreated'] = time();
        $this->adapter->prepare($this->getInsertGenericSql(['content', 'timeCreated']));
        $this->adapter->bindParam(':content', $data['content'], $this->adapter->getParamStr());
        $this->adapter->bindParam(':timeCreated', $data['timeCreated'], $this->adapter->getParamInt());
        $this->adapter->execute();
        $data['id'] = $this->adapter->getLastInsertId();
        return $this->getModelLazy($data);
    }


    public function updateById(\Mwyatt\Core\Model\Log $model)
    {
        $this->adapter->prepare($this->getUpdateGenericSql(['content']));
        $this->adapter->bindParam(':content', $model->get('content'), $this->adapter->getParamStr());
        $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
        $this->adapter->execute();
        return $this->adapter->getRowCount();
    }
}

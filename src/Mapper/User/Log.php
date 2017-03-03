<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\AbstractMapper
{
    protected $publicCols = [
        'userId' => \PDO::PARAM_INT,
        'logId' => \PDO::PARAM_INT
    ];


    private function validateModel(\Mwyatt\Core\ModelInterface $model)
    {
        $errors = [];
        if (strlen($model->get('userId')) < 1) {
            $errors[] = 'Must have userId.';
        }
        if (strlen($model->get('logId')) < 1) {
            $errors[] = 'Must have logId.';
        }
        if ($errors) {
            throw new \Exception('Log validation errors: ' . implode(' ', $errors));
        }
    }


    public function findByUserIds(array $userIds)
    {
        $models = [];
        $this->adapter->prepare("select * from `{$this->getTableNameLazy()}` where `userId` = ?");
        foreach ($userIds as $userId) {
            $this->adapter->bindParam(1, $userId, $this->publicCols['userId']);
            $this->adapter->execute();
            while ($model = $this->adapter->fetch($this->adapter->getFetchTypeClass(), $this->getModelClassAbs())) {
                $models[] = $model;
            }
        }
        return $this->getIterator($models);
    }
}

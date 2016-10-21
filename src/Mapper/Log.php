<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\AbstractMapper
{
    protected $publicCols = [
        'content' => \PDO::PARAM_STR,
        'timeCreated' => \PDO::PARAM_INT
    ];


    private function validateModel(\Mwyatt\Core\ModelInterface $model)
    {
        $errors = [];
        if (strlen($model->get('content')) < 1) {
            $errors[] = 'Must have content.';
        }
        if ($errors) {
            throw new \Exception('Log validation errors: ' . implode(' ', $errors));
        }
    }
}

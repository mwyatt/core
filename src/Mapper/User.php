<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{
    protected $publicCols = [
        'email' => \PDO::PARAM_STR,
        'password' => \PDO::PARAM_STR,
        'timeCreated' => \PDO::PARAM_INT,
        'nameFirst' => \PDO::PARAM_STR,
        'nameLast' => \PDO::PARAM_STR
    ];


    private function validateModel(\Mwyatt\Core\Model\User $model)
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
}

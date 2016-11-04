<?php

namespace Mwyatt\Core\Http;

class Config
{
    protected $data = [
        'projectBaseNamespace' => 'Mwyatt\\Core\\',
        'controllerErrorClass' => \Mwyatt\Core\Controller\Error::class,
    ];
    

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function getSetting($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }


    public function setSetting($key, $value)
    {
        $this->data[$key] = $value;
    }
}

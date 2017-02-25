<?php

namespace Mwyatt\Core\Http;

class Config
{
    protected $data = [
        'projectBaseNamespace' => 'Mwyatt\\Core\\',
        'controllerErrorClass' => \Mwyatt\Core\Controller\Error::class,
        'core.routes.path' => 'routes.php',
    ];
    

    public function __construct(array $data = [])
    {
        $this->setSettings($data);
    }


    private function setSettings(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setSetting($key, $value);
        }
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

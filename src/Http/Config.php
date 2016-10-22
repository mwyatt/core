<?php

namespace Mwyatt\Core\Http;

class Config
{
    protected $data;
    

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
}

<?php

namespace Mwyatt\Core\Model;

trait IdTrait
{
    protected $id;


    public function setId($value)
    {
        $value = $value + 0;
        $this->id = $value;
    }


    private function validateId()
    {
        if ($this->id < 1) {
            $this->appendError("Id '$this->id' is invalid.");
        }
    }
}

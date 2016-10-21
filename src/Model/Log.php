<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\ModelInterface
{
    protected $id;
    protected $content;
    protected $timeCreated;


    public function setId($value)
    {
        $value = $value + 0;
        if (!$value) {
            throw new \Exception("Log id '$value' is invalid.");
        }
        $this->id = $value;
    }


    public function getTimeCreated()
    {
        if (!$this->timeCreated) {
            $this->timeCreated = time();
        }
        return $this->timeCreated;
    }


    public function setContent($value)
    {
        if (strlen($value) < 3) {
            return;
        } elseif (strlen($value) > 255) {
            return;
        }
        $this->content = $value;
    }


    public function setTimeCreated($value)
    {
        $this->timeCreated = $value;
    }
}

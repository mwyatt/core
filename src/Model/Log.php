<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\ModelInterface
{
    use \Mwyatt\Core\Model\IdTrait;

    protected $content;
    protected $timeCreated;


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

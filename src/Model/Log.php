<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel
{
    protected $id;
    protected $content;
    protected $timeCreated;


    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : '';
        $this->setContent($data['content']);
        $this->setTimeCreated($data['timeCreated']);
        return $this;
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

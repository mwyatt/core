<?php

namespace Mwyatt\Core\Model;

class File extends \Mwyatt\Core\AbstractModel
{


    protected $pathAbsolute;
    protected $pathRelative;
    protected $title;
    protected $isDirectory;
    protected $timeModified;
    protected $pathDirectory;


    public function __construct(
        $pathAbsolute,
        $pathRelative,
        $title,
        $isDirectory
    ) {
    
        $this->pathAbsolute = $pathAbsolute;
        $this->pathRelative = $pathRelative;
        $this->title = $title;
        $this->isDirectory = $isDirectory;
    }


    public function setTimeModified($timeModified = 0)
    {
        $this->timeModified = $timeModified ? $timeModified : filemtime($this->get('pathAbsolute'));
    }


    public function setPathDirectory()
    {
        $this->pathDirectory = str_replace($this->pathAbsolute, '', $this->pathRelative);
    }
}

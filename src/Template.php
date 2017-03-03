<?php

namespace Mwyatt\Core;

class Template implements \Mwyatt\Core\TemplateInterface
{
    protected $projectPath;
    protected $view;


    public function __construct(
        $projectPath,
        \Mwyatt\Core\ViewInterface $view
    ) {
    
        $this->projectPath = $projectPath;
        $this->view = $view;
    }


    public function getTemplate($template)
    {
        return $this->view->getTemplateFilePath($template);
    }


    public function getPath($append = '')
    {
        return $this->projectPath . $append;
    }
}

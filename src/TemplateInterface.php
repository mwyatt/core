<?php

namespace Mwyatt\Core;

interface TemplateInterface
{
    public function __construct(
        $projectPath,
        \Mwyatt\Core\ViewInterface $view
    );
    public function getTemplate($template);
    public function getPath($append = '');
}

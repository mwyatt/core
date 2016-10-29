<?php

namespace Mwyatt\Core;

interface ViewInterface
{
    public function __construct($defaultTemplateDirectory);
    public function appendTemplateDirectory($directory);
    public function prependTemplateDirectory($directory);
    public function getTemplateDirectoriesTotal();
    public function getTemplate($templatePath);
    public function getTemplateFilePath($filename, $ext = 'php');
}

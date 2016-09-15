<?php

namespace Mwyatt\Core;

interface ViewInterface
{
    public function __construct();
    public function setPathBase($path);
    public function getPathBase($append = '');
    public function getPathBasePackage($append = '');
    public function appendTemplatePath($path);
    public function getTemplate($templatePath);
    public function getPathTemplate($append, $ext = 'php');
    public function appendAsset($type, $path);
}

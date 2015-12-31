<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
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

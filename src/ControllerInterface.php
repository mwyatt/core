<?php

namespace Mwyatt\Core;

interface ControllerInterface
{
    public function __construct(
        \Pimple\Container $pimpleContainer,
        \Mwyatt\Core\ViewInterface $view
    );
    public function getService($name);
    public function getView($name);
    public function response($content = '', $statusCode = 200);
    public function redirect($key, $config = [], $statusCode = 302);
    public function redirectAbs($url, $statusCode = 302);
    public function render($templatePath);
}

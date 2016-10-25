<?php

define('PATH_BASE', (string) (__DIR__ . '/'));
include PATH_BASE . 'vendor/autoload.php';
$kernel = new \Mwyatt\Core\Http\Kernel(PATH_BASE);
$kernel->registerMiddleware([
    'admin.auth' => \Mwyatt\Core\Middleware\Admin::class
]);
$kernel->route();

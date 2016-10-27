<?php

$basePath = (string) (__DIR__ . '/');
include $basePath . 'vendor/autoload.php';
$kernel = new \Mwyatt\Core\Http\Kernel;
$kernel->setServiceProjectPath($basePath);
$kernel->setServicesEssential();
$kernel->setServices($basePath . 'services.php');
$kernel->setSettings([
    'projectBaseNamespace' => 'Mwyatt\\Core\\'
]);
$kernel->setRoutes([
    [
        'any', '/',
        '\\Mwyatt\\Core\\Controller\\Test', 'index',
        ['id' => 'home', 'middleware' => ['common', 'admin.auth']]
    ],
    [
        'any', '/foo/:name/:id/',
        '\\Mwyatt\\Core\\Controller\\Test', 'testParams',
        ['id' => 'test.params', 'middleware' => ['common']]
    ]
]);
$kernel->setMiddleware([
    'common' => \Mwyatt\Core\Middleware\Common::class,
    'admin.auth' => \Mwyatt\Core\Middleware\Admin::class
]);
$kernel->route();

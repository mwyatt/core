<?php

define('PATH_BASE', (string) (__DIR__ . '/'));
include PATH_BASE . 'vendor/autoload.php';
$kernel = new \Mwyatt\Core\Http\Kernel(PATH_BASE);
$kernel->setRoutes([
    [
        'any', '/',
        '\\Mwyatt\\Core\\Controller\\Test', 'testSimple',
        ['id' => 'test.simple', 'middleware' => ['admin.auth']]
    ],
    [
        'any', '/foo/:name/:id/',
        '\\Mwyatt\\Core\\Controller\\Test', 'testParams',
        ['id' => 'test.params']
    ],
    [
        'post', '/foo/bar/',
        '\\Mwyatt\\Core\\Controller\\Test', 'testSimple'
    ]
]);
$kernel->registerMiddleware([
    'admin.auth' => \Mwyatt\Core\Middleware\Admin::class
]);
$kernel->registerSettings([
    'model.factory.namespace' => 'Mwyatt\\Core\\Model\\',
    'iterator.factory.namespace' => 'Mwyatt\\Core\\Iterator\\',
    'mapper.factory.namespace' => 'Mwyatt\\Core\\Iterator\\',
    'repository.factory.namespace' => 'Mwyatt\\Core\\Repository\\'
]);
$kernel->route();

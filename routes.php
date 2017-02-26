<?php

return [
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
];

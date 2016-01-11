<?php

return [
    [
        'any', '',
        '\\Mwyatt\\Core\\Controller\\Test', 'testSimple',
        ['id' => 'test.simple']
    ],
    [
        'any', 'foo/:name/:id/',
        '\\Mwyatt\\Core\\Controller\\Test', 'testParams',
        ['id' => 'test.params']
    ],
    [
        'post', 'foo/bar/',
        '\\Mwyatt\\Core\\Controller\\Test', 'testSimple'
    ]
];

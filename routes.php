<?php

$routes[] = [
    'get',
    '/404/',
    'Mwyatt\\Elttl\\Controller\\Error', 'notFound',
    ['id' => 'error.notFound', 'middleware' => 'admin.auth']
];

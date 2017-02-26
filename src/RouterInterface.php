<?php

namespace Mwyatt\Core;

interface RouterInterface
{
    public function __construct(
        \Pux\Mux $mux,
        array $routes
    );
    public function getMatch($path);
    public function getRoutes();
    public function redirect($url, $statusCode = 302);
}

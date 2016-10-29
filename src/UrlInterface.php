<?php

namespace Mwyatt\Core;

interface UrlInterface
{
    public function __construct(
        $host,
        $installPathQuery,
        $install = ''
    );
    public function getPath();
    public function getQueryArray();
    public function setRoutes(array $routes);
    public function generate($key = '', $config = [], array $query = []);
    public function generateVersioned($pathBase, $pathAppend);
}

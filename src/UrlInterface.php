<?php

namespace Mwyatt\Core;

interface UrlInterface
{
    public function __construct(
        \Mwyatt\Core\RouterInterface $router,
        $host,
        $installPathQuery,
        $install = ''
    );
    public function getPath();
    public function getQueryArray();
    public function generate($key = '', $config = [], array $query = []);
    public function generateVersioned($pathBase, $pathAppend);
}

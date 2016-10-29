<?php

namespace Mwyatt\Core;

interface MiddlewareInterface
{
    public function handle(\Mwyatt\Core\RequestInterface $request);
}

<?php

namespace Mwyatt\Core;

interface ModelInterface
{
    public function __construct(array $data);
    public function get($property);
}

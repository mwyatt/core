<?php

namespace Mwyatt\Core;

interface ObjectIteratorInterface
{
    public function getKeyedByProperty($property);
    public function getKeyedByPropertyMulti($property);
    public function filterOutByPropertyValue($property, $value);
    public function extractProperty($property);
    public function getByPropertyValue($property, $value);
}

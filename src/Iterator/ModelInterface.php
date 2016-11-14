<?php

namespace Mwyatt\Core\Iterator;

interface ModelInterface
{
    public function getIds();
    public function getById($id);
    public function extractProperty($property);
    public function extractPropertyUnique($property);
    public function getByPropertyValues($property, array $values);
    public function getKeyedByProperty($property);
    public function getKeyedByPropertyMulti($property);
}

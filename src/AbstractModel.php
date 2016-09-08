<?php

namespace Mwyatt\Core;

abstract class AbstractModel implements \Mwyatt\Core\ModelInterface
{


    public function __construct(array $data)
    {
        
    }


    protected function checkDataKeys($data, $keys)
    {
        $missingKeys = [];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                $missingKeys[] = $key;
            }
        }
        if ($missingKeys) {
            throw new \Exception('Missing data keys (' . implode(', ', $missingKeys) . ').');
        }
    }


    /**
     * think about how this could be injected
     */
    protected function getAssertionChain($value)
    {
        return \Assert\that($value);
    }


    /**
     * get property via method if in place or just the property
     * @param  string $property
     * @return mixed
     */
    public function get($property)
    {
        $proposedMethod = 'get' . ucfirst($property);

        if (method_exists($this, $proposedMethod)) {
            return $this->$proposedMethod();
        }

        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}

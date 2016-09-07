<?php

namespace Mwyatt\Core;

abstract class AbstractIterator extends \ArrayIterator implements JsonSerializable
{

    /* Methods */
    // public void append ( mixed $value )
    // public void asort ( void )
    // public __construct ([ mixed $array = array() [, int $flags = 0 ]] )
    // public int count ( void )
    // public mixed current ( void )
    // public array getArrayCopy ( void )
    // public void getFlags ( void )
    // public mixed key ( void )
    // public void ksort ( void )
    // public void natcasesort ( void )
    // public void natsort ( void )
    // public void next ( void )
    // public void offsetExists ( string $index )
    // public mixed offsetGet ( string $index )
    // public void offsetSet ( string $index , string $newval )
    // public void offsetUnset ( string $index )
    // public void rewind ( void )
    // public void seek ( int $position )
    // public string serialize ( void )
    // public void setFlags ( string $flags )
    // public void uasort ( string $cmp_function )
    // public void uksort ( string $cmp_function )
    // public string unserialize ( string $serialized )
    // public bool valid ( void )

    public function jsonSerialize() {
        return $this->getArrayCopy();
    }
}

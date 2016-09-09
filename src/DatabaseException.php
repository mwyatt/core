<?php

namespace Mwyatt\Core;

class DatabaseException extends \Exception
{
    protected $message = 'Problem while communicating with storage adapter.';
}

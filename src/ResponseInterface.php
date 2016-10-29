<?php

namespace Mwyatt\Core;

interface ResponseInterface
{
    public function __construct($content = '', $statusCode = 200);
    public function getContent();
    public function getStatusCode();
    public function setStatusCode($statusCode);
    public function setHeader($header);
}

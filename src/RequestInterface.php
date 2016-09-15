<?php

namespace Mwyatt\Core;

interface RequestInterface
{
    public function __construct();
    public function setFiles(array $files);
    public function getQuery($key);
    public function getUrlVar($key);
    public function setUrlVar($key, $value);
    public function setMuxUrlVars(array $route);
    public function getPost($key);
    public function getCookie($key);
    public function getServer($key);
    public function getSession($key);
    public function getFiles();
    public function getBody();
    public function isPost();
}

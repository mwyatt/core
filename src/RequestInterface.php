<?php

namespace Mwyatt\Core;

interface RequestInterface
{
    public function __construct(
        \Mwyatt\Core\SessionInterface $session,
        \Mwyatt\Core\CookieInterface $cookie
    );
    public function getQuery($key, $default = null);
    public function getPost($key, $default = null);
    public function getCookie($key, $default = null);
    public function setCookie($key, $value, $time);
    public function getServer($key, $default = null);
    public function getUrlVar($key, $default = null);
    public function setUrlVar($key, $value);
    public function setMuxUrlVars(array $route = []);
    public function getSession($key, $default = null);
    public function setSession($key, $value);
    public function pullSession($key, $default = null);
    public function getFiles();
    public function getBody();
    public function isPost();
}

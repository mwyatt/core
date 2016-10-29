<?php

namespace Mwyatt\Core;

class Request implements \Mwyatt\Core\RequestInterface
{
    public $session;
    public $cookie;
    protected $query;
    protected $post;
    protected $server;
    protected $urlVars = [];
    protected $body;
    protected $files = [];


    public function __construct(
        \Mwyatt\Core\SessionInterface $session,
        \Mwyatt\Core\CookieInterface $cookie
    )
    {
        $this->query = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $this->setFiles($_FILES);
        $this->session = $session;
        $this->cookie = $cookie;
    }


    /**
     * store the request files in a nice way
     * @param array $files
     */
    protected function setFiles(array $files)
    {
        foreach ($files as $associativeName => $details) {
            foreach ($details as $fileKey => $detail) {
                $this->files[$fileKey][$associativeName] = $detail;
            }
        }
    }


    public function getQuery($key, $default = null)
    {
        return $this->getPropKey('query', $key, $default);
    }


    public function getPost($key, $default = null)
    {
        return $this->getPropKey('post', $key, $default);
    }


    public function getCookie($key, $default = null)
    {
        return $this->getPropKey('cookie', $key, $default);
    }


    public function setCookie($key, $value, $time)
    {
        setcookie($key, $value, $time);
    }


    public function getServer($key, $default = null)
    {
        return $this->getPropKey('server', $key, $default);
    }


    public function getUrlVar($key, $default = null)
    {
        return $this->getPropKey('urlVars', $key, $default);
    }


    public function setUrlVar($key, $value)
    {
        $this->urlVars[$key] = $value;
    }


    public function setMuxUrlVars(array $route = [])
    {
        if (empty($route[3]['vars'])) {
            return;
        }
        foreach ($route[3]['vars'] as $key => $value) {
            if (!is_int($key)) {
                $this->setUrlVar($key, $value);
            }
        }
    }


    public function getSession($key, $default = null)
    {
        return $this->session->get($key, $default);
    }


    public function setSession($key, $value)
    {
        $this->session->set($key, $value);
    }


    public function pullSession($key, $default = null)
    {
        return $this->session->pull($key, $default);
    }


    public function getFiles()
    {
        return $this->files;
    }


    public function getBody()
    {
        if (!$this->body) {
            $this->body = file_get_contents('php://input');
        }
        return $this->body;
    }


    private function getPropKey($type, $key, $default = null)
    {
        return isset($this->{$type}[$key]) ? $this->{$type}[$key] : $default;
    }


    public function isPost()
    {
        return $_POST;
    }
}

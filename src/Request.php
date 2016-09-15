<?php
namespace Mwyatt\Core;

class Request implements \Mwyatt\Core\RequestInterface
{

    // should these be protected?
    public $session;
    public $cookie;

    protected $query;
    protected $post;
    protected $server;
    protected $urlVars = [];
    protected $body;
    protected $files = [];


    /**
     * init all properties to be accessed during request
     */
    public function __construct()
    {
        $this->query = $_GET;
        $this->post = $_POST;
        $this->cookie = new \Mwyatt\Core\Cookie;
        $this->session = new \Mwyatt\Core\Session;
        $this->server = $_SERVER;
        $this->files = $this->setFiles($_FILES);
    }


    /**
     * store the request files in a nice way
     * @param array $files
     */
    public function setFiles(array $files)
    {
        foreach ($files as $associativeName => $details) {
            foreach ($details as $fileKey => $detail) {
                $this->files[$fileKey][$associativeName] = $detail;
            }
        }
    }


    /**
     * deprecated for 'getQuery'
     * @param  string $key
     * @return string
     */
    public function get($key, $default = null)
    {
        return $this->getQuery($key);
    }


    public function getQuery($key, $default = null)
    {
        return $this->getPropKey('query', $key, $default);
    }


    public function getUrlVar($key, $default = null)
    {
        return $this->getPropKey('urlVars', $key, $default);
    }


    public function setUrlVar($key, $value)
    {
        $this->urlVars[$key] = $value;
    }


    public function setMuxUrlVars(array $route)
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


    /**
     * is this a post request?
     * @return boolean
     */
    public function isPost()
    {
        return $_POST;
    }
}

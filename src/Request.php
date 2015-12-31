<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Request // implements \Mwyatt\Core\RequestInterface
{


    private $get;


    private $post;


    private $cookie;


    private $server;


    private $session;


    private $urlVars = [];


    public $body;


    public $files = [];


    /**
     * init all properties to be accessed during request
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->server = $_SERVER;
        $this->session = $_SESSION;
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


    public function get($key)
    {
        return $this->getPropKey('get', $key);
    }


    public function getUrlVar($key)
    {
        return $this->getPropKey('urlVars', $key);
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


    public function getPost($key)
    {
        return $this->getPropKey('post', $key);
    }


    public function getCookie($key)
    {
        return $this->getPropKey('cookie', $key);
    }


    public function getServer($key)
    {
        return $this->getPropKey('server', $key);
    }


    public function getSession($key)
    {
        return $this->getPropKey('session', $key);
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


    private function getPropKey($type, $key)
    {
        return empty($this->{$type}[$key]) ? null : $this->{$type}[$key];
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

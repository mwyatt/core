<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Request implements \Mwyatt\Core\RequestInterface
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
        return $this->getPropKey('get', $key, $default);
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


    public function getServer($key, $default = null)
    {
        return $this->getPropKey('server', $key, $default);
    }


    public function getSession($key, $default = null)
    {
        return $this->getPropKey('session', $key, $default);
    }


    /**
     * set a single session key
     * is this enough?
     * @param string $key
     * @param mixed $value
     */
    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
        $this->session[$key] = $value;
    }


    /**
     * removes session key and passes back the value
     * @param  string $key
     * @return mixed
     */
    public function pullSession($key, $default = null)
    {
        if (empty($_SESSION[$key])) {
            return $default;
        }
        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        unset($this->session[$key]);
        return $value;
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
        return isset($this->{$type}[$key]) ? $this->{$type}[$key] : null;
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

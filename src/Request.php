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

        // here?
        $this->session = $_SESSION;
        $this->files = $this->setFiles($_FILES);
    }


    /**
     * store the request files in a nice way
     * @param array $files 
     */
    public function setFiles($files)
    {
        if (!$files) {
            return;
        }
        foreach ($files as $associativeName => $details) {
            foreach ($details as $fileKey => $detail) {
                $this->files[$fileKey][$associativeName] = $detail;
            }
        }
    }


    /**
     * lazy get is this a good idea?
     * @param  string $key 
     * @return mixed      
     */
    public function get($key)
    {
        if (!empty($this->get[$key])) {
            return $this->get[$key];
        } elseif (!empty($this->post[$key])) {
            return $this->post[$key];
        } elseif (!empty($this->cookie[$key])) {
            return $this->cookie[$key];
        } elseif (!empty($this->server[$key])) {
            return $this->server[$key];
        }
    }


    public function getPost($key)
    {
        $type = 'post';
        return empty($this->$type[$key]) ? null : $this->$type[$key];
    }


    public function getCookie($key)
    {
        $type = 'cookie';
        return empty($this->$type[$key]) ? null : $this->$type[$key];
    }

    public function getServer($key)
    {
        $type = 'server';
        return empty($this->$type[$key]) ? null : $this->$type[$key];
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


    public function getType()
    {
        if ($_POST) {
            return 'post';
        } elseif ($_GET) {
            return 'get';
        }
    }
}

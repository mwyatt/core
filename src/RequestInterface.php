<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class RequestInterface
{


    public function __construct();
    public function setFiles(array $files);
    public function get($key);
    public function getUrlVar($key);
    public function setUrlVar($key, $value);
    public function getPost($key);
    public function getCookie($key);
    public function getServer($key);
    public function getFiles();
    public function getBody();
    public function getType();
}

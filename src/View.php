<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class View extends \Mwyatt\Core\Data implements ViewInterface
{


    /**
     * reference to url to allow building urls in templates
     * @var object  \Mwyatt\Core\Url
     */
    public $url;


    protected $templatePaths = [];


    protected $path;


    protected $assetTypes = ['mustache', 'css', 'js'];


    /**
     * must store the routes found in the registry for building urls
     * always prepend this package template path
     */
    public function __construct(\Mwyatt\Core\Url $url)
    {
        $this->url = $url;
        $this->path = (string) (__DIR__ . '/../');
        $this->prependTemplatePath($this->path . 'template/');
    }
    

    /**
     * while searching for templates it will look through an array
     * of paths
     * throws exception if the path does not exist or is not a directory
     * @param  string $path 
     * @return object       
     */
    public function prependTemplatePath($path)
    {
        if (!is_dir($path)) {
            throw new \Exception('path does not exist');
        }
        array_unshift($this->templatePaths, $path);
        return $this;
    }


    /**
     * while searching for templates it will look through an array
     * of paths
     * throws exception if the path does not exist or is not a directory
     * @param  string $path 
     * @return object       
     */
    public function appendTemplatePath($path)
    {
        if (!is_dir($path)) {
            throw new \Exception('path does not exist');
        }
        $this->templatePaths[] = $path;
        return $this;
    }

    
    /**
     * load template file and prepare all objects for output
     * @param  string $templatePath
     */
    public function getTemplate($templatePath)
    {

        // obtain path
        $path = $this->getPathTemplate($templatePath);
        if (!$path) {
            return;
        }

        // push stored into method scope
        extract($this->getData());

        // start output buffer
        // @todo start this at the start of the app?
        ob_start();

        // render template using extracted variables
        include($path);
        $content = ob_get_contents();

        // destroy output buffer
        // @todo convert to ob_clean
        ob_end_clean();

        // add this data to existing
        $this->setData($content);

        // return just loaded template result
        return $content;
    }


    /**
     * gets just the base file path
     * @param  string $append
     * @return string
     */
    public function getPath($append = '')
    {
        return $this->path . $append;
    }


    /**
     * finds a template in the dependant
     * falls back to this package template
     * else exception
     * @param  string $append    foo/bar
     * @return string            the path
     */
    public function getPathTemplate($append, $ext = 'php')
    {
        $end = strtolower($append) . '.' . $ext;
        foreach ($this->templatePaths as $path) {
            $path .= $end;
            if (file_exists($path)) {
                return $path;
            }
        }
        throw new \Exception("unable to find template '$path'");
    }


    /**
     * allows easy registering of additional asset paths
     * these can be then added in order inside the skin
     * header/footer
     * @param  string $type mustache|css|js
     * @param  string $path foo/bar
     * @return object
     */
    public function appendAsset($type, $path)
    {

        // validate
        if (!in_array($type, $this->assetTypes)) {
            return $this;
        }

        // set
        $rootKey = 'asset';
        if (!isset($this->data[$rootKey])) {
            $this->data[$rootKey] = [];
        }
        if (!isset($this->data[$rootKey][$type])) {
            $this->data[$rootKey][$type] = [];
        }
        $this->data[$rootKey][$type][] = $path;
        return $this;
    }
}

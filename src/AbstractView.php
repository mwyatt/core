<?php

namespace Mwyatt\Core;

abstract class AbstractView implements \ArrayIterator, \Mwyatt\Core\ViewInterface
{


    /**
     * the paths which will be cycled through when looking for a template
     * @var array
     */
    protected $templatePaths = [];


    /**
     * the base path for this package
     * @var string
     */
    protected $pathBasePackage;


    /**
     * the base path for the dependee
     * @var string
     */
    protected $pathBase;


    /**
     * define data and pathbasepackage
     */
    public function __construct()
    {
        $this->pathBasePackage = (string) __DIR__ . '/../';
        $this->appendTemplatePath($this->getPathBasePackage('template/'));
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
        $this->testTemplatePath($path);
        $this->templatePaths[] = $path;
        return $this;
    }


    /**
     * prepends the path so it takes prioroty over other template paths
     * @param  string
     * @return object
     */
    public function prependTemplatePath($path)
    {
        $this->testTemplatePath($path);
        $countOld = count($this->templatePaths);
        $countNew = array_unshift($this->templatePaths, $path);
        return $countNew == ($countOld + 1);
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
        extract($this->getArrayCopy());

        // start output buffer
        // @todo start this at the start of the app?
        ob_start();

        // render template using extracted variables
        include $path;
        $content = ob_get_contents();

        // destroy output buffer
        ob_end_clean();

        // return render codes
        return $content;
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
        $path = '';
        foreach ($this->templatePaths as $path) {
            $path .= $end;
            if (file_exists($path)) {
                return $path;
            }
        }
        throw new \Exception("template file '$path' does not exist");
    }


    /**
     * adds a value to an offset array, if there is an array
     * @param  mixed $index 
     * @param  mixed $value 
     * @return bool        
     */
    public function offsetAppend($index, $value)
    {
        $itemOffset = $this->offsetGet($index);
        $items = $itemOffset ? $itemOffset : [];
        if (!is_array($items)) {
            throw new \Exception("View offset $index is not an array.");
        }
        $items[] = $value;
        return $this->offsetSet($index, $items);
    }


    /**
     * gets just the base file path
     * named nicer for templates but does this make sense?
     * @param  string $append
     * @return string
     */
    public function getPathBase($append = '')
    {
        return $this->pathBase . $append;
    }


    /**
     * stores the path for the dependee
     * @param string $path
     */
    public function setPathBase($path)
    {
        $this->pathBase = $path;
        $this->appendTemplatePath($path);
    }


    /**
     * base path for the package
     * @param  string $append
     * @return string
     */
    public function getPathBasePackage($append = '')
    {
        return $this->pathBasePackage . $append;
    }


    /**
     * ensures that the path is a directory
     * @param  string $path
     * @return null       may throw exception
     */
    protected function testTemplatePath($path)
    {
        if (!is_dir($path)) {
            throw new \Exception("path '$path' does not exist");
        }
    }
}

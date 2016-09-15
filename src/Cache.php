<?php

namespace Mwyatt\Core;

/**
 * handle large collections of objects and or arrays
 * these will be easily accessible by storing in files '-' delimiter
 */
class Cache implements \Mwyatt\Core\CacheInterface
{


    /**
     * folder house for cache
     * @var string
     */
    protected $dirDefault = 'cache/';


    /**
     * the base path for this package
     * @var string
     */
    protected $pathBase;


    protected $data;


    /**
     * fire in a storage path which cache will work with
     * @param string $key path/foo-bar
     */
    public function __construct($path = '')
    {
        if (!$path) {
            $path = (string) (__DIR__ . '/../') . $this->dirDefault;
        }
        $this->setPathBase($path);
    }


    public function setPathBase($path)
    {
        $this->isWritable($path);
        $this->pathBase = $path;
    }


    public function getPathBase($append = '')
    {
        return $this->pathBase . $append;
    }


    protected function isWritable($path)
    {
        if (!is_writable($path)) {
            throw new \Exception("Cache path '$path' is not writable.");
        }
    }


    /**
     * serialises and creates cache file if required
     * if the file already exists, skip this
     * @param  string $key  example-file-name
     * @param  array $data
     * @return bool
     */
    public function create($fileName, $data)
    {
        $path = $this->getPathBase($fileName);

        if (file_exists($path)) {
            return;
        }

        $data = serialize($data);

        return file_put_contents($path, $data);
    }


    /**
     * reads in the cached file, if it exists
     * unserialises and stores in data property
     * @param  string $key example-file-name
     * @return bool
     */
    public function read($fileName)
    {
        $path = $this->getPathBase($fileName);

        if (!file_exists($path)) {
            return;
        }

        $data = file_get_contents($path);
        return $this->data = unserialize($data);
    }


    /**
     * removes the file from the cache
     * @param  string $key
     * @return bool
     */
    public function delete($fileName)
    {
        $path = $this->getPathBase($fileName);

        if (!file_exists($path)) {
            return;
        }

        return unlink($path);
    }
}

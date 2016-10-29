<?php

namespace Mwyatt\Core;

class FileSystem
{
    private $pathBase;


    public function __construct($path)
    {
        $this->setPathBase($path);
    }


    public function setPathBase($path)
    {
        $this->validatePath($path);
        $this->pathBase = $path;
    }


    public function getFile($pathAbsolute)
    {
        $this->validatePath($pathAbsolute);

        $fileInfo = pathinfo($pathAbsolute);
        $file = new \Mwyatt\Core\Model\File(
            $pathAbsolute,
            str_replace($this->pathBase, '', $pathAbsolute),
            $fileInfo['basename'],
            is_dir($pathAbsolute)
        );

        return $file;
    }


    public function getFileDetailed($path)
    {
        $file = $this->getFile($path);
        $file->setTimeModified();
        $file->setPathDirectory();
        
        return $file;
    }


    public function getDirectory($pathRelative = '')
    {
        $pathAbsolute = $this->pathBase . $pathRelative;
        $this->validatePath($pathAbsolute);

        $files = [];
        
        $paths = glob($pathAbsolute . '/*');

        foreach ($paths as $path) {
            $files[] = $this->getFile($path);
        }

        return new \Mwyatt\Core\ObjectIterator($files);
    }


    public function deleteFile($path)
    {
        $this->validatePath($path);

        return unlink($path);
    }


    public function deleteDirectory($path)
    {
        $this->validatePath($path);

        if (!is_dir($path)) {
            throw new \Exception("'$path' is not a directory.");
        }

        if (count(scandir($dir)) !== 2) {
            throw new \Exception("'$path' is not empty.");
        }

        return rmdir($path);
    }


    private function validatePath($path)
    {
        if (!is_readable($path)) {
            throw new \Exception("'$path' is unreadable.");
        }
    }


    /**
     * watch out can be slow!
     * 4.75s for 20k paths
     * @param  string $pattern '/*'
     * @return array          paths
     */
    private function globRecursive($pattern)
    {
        $paths = glob($pattern);
        foreach ($paths as $path) {
            if (strpos($path, '.') === false) {
                $paths = array_merge($paths, $this->globRecursive($path . '/*'));
            }
        }
        return $paths;
    }
}

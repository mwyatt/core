<?php

namespace Mwyatt\Core;

abstract class AbstractView extends \Mwyatt\Core\AbstractIterator implements \Mwyatt\Core\ViewInterface
{
    protected $templateDirectories = [];


    public function __construct($defaultTemplateDirectory)
    {
        $this->appendTemplateDirectory($defaultTemplateDirectory);
    }


    public function appendTemplateDirectory($directory)
    {
        $this->validateTemplateDirectory($directory);
        $this->templateDirectories[] = $directory;
    }


    /**
     * prepends the directory so it takes prioroty over other template paths
     * @param  string
     * @return object
     */
    public function prependTemplateDirectory($directory)
    {
        $this->validateTemplateDirectory($directory);
        array_unshift($this->templateDirectories, $directory);
    }


    public function getTemplateDirectoriesTotal()
    {
        return count($this->templateDirectories);
    }

    
    /**
     * converts stored key values in iterator to output from
     * template path requested
     * @param  string $templatePath
     * @return string
     */
    public function getTemplate($templatePath)
    {
        $absoluteTemplateFilePath = $this->getTemplateFilePath($templatePath);
        if (!$absoluteTemplateFilePath) {
            return;
        }
        extract($this->getArrayCopy());
        ob_start();
        include $absoluteTemplateFilePath;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }


    public function getTemplateFilePath($filename, $ext = 'php')
    {
        $templateDirectories = $this->templateDirectories;
        $end = $filename . '.' . $ext;
        $proposedPath = '';
        foreach ($templateDirectories as $templateDirectory) {
            $proposedPath = $templateDirectory . $end;
            if (file_exists($proposedPath)) {
                return $proposedPath;
            }
        }
        throw new \Exception("Template '$path' does not exist.");
    }


    /**
     * throws exception if invalid
     * @param  string $directory
     */
    protected function validateTemplateDirectory($directory)
    {
        if (!is_dir($directory)) {
            throw new \Exception("Template directory '$directory' does not exist.");
        }
    }
}

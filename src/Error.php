<?php

namespace Mwyatt\Core;

// set_error_handler(array($this, 'handle'));
        //     ini_set('display_errors', 1);
        //     ini_set('display_startup_errors', 1);
        //     error_reporting(E_ALL);
            // debug_print_backtrace()
class Error
{


    public $pathBase;
    public $pathFile;


    public function __construct($pathFile = '')
    {
        $pathBase = (string) (__DIR__ . '/../');
        if (!$pathFile) {
            $pathFile = $pathBase . 'error.txt';
        }
        $this->setPathFile($pathFile);
        $this->pathFile = $pathFile;
        $this->pathBase = $pathBase;
    }


    public function setPathFile($pathFile)
    {
        $this->isWritable($pathFile);
        $this->pathFile = $pathFile;
    }


    protected function isWritable($path)
    {
        if (!is_writable($path)) {
            throw new \Exception("Path '$path' is not writable.");
        }
    }


    public function handle($errorType, $errorString, $errorFile, $errorLine)
    {
        $lines = file_get_contents($pathFile);
        $date = date('d/m/Y', time());
        $line = "[Type $errorType] $errorString | $errorFile [Line $errorLine] [Date $date]\n";
        return file_put_contents($this->pathFile, $lines . $line);
    }
}

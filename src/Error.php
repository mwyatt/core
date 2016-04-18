<?php

namespace Mwyatt\Core;

class Error
{

    
    /**
     * @var boolean
     */
    protected $reporting;


    public function __construct($report)
    {
        $this->reporting = $report;
        set_error_handler(array($this, 'handle'));

        if ($this->reporting) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }


    public function handle($errorType, $errorString, $errorFile, $errorLine)
    {
        if ($this->reporting) {
            echo '<pre>';
            print_r($errorType);
            echo '</pre>';
            echo '<pre>';
            print_r($errorString);
            echo '</pre>';
            echo '<pre>';
            print_r($errorFile);
            echo '</pre>';
            echo '<pre>';
            print_r($errorLine);
            echo '</pre>';
            exit;

            // display error(s)
            echo '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . ']' . "\n";

            // trying this out
            echo '<pre>';
            print_r(debug_print_backtrace());
            echo '</pre>';
            exit;
        }
        
        // put error info and echo friendly schpiel
        file_put_contents(BASE_PATH . 'error.txt', file_get_contents(BASE_PATH . 'error.txt') . '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . '] [Date ' . date('d/m/Y', time()) . ']' . "\n");
        echo 'A error has occurred. We all make mistakes. Please notify the administrator <a href="mailto:martin.wyatt@gmail.com">martin.wyatt@gmail.com</a>';
    }
}

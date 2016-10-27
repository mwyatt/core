<?php

namespace Mwyatt\Core\Middleware;

class Admin extends \Mwyatt\Core\AbstractMiddleware
{


    public function handle(\Mwyatt\Core\RequestInterface $request)
    {
        echo "Admin Auth Handle";
    }


    public function terminate(
        \Mwyatt\Core\RequestInterface $request,
        \Mwyatt\Core\ResponseInterface $response
    ) {
    
        // does something?
    }
}

<?php

namespace Mwyatt\Core;

class AbstractMiddleware
{


    public function __construct()
    {
        
    }
    
    
    public function handle(\Mwyatt\Core\RequestInterface $request)
    {
        // does something or redirects?
    }


    public function terminate(
        \Mwyatt\Core\RequestInterface $request,
        \Mwyatt\Core\ResponseInterface $response
    ) {
    
        // does something?
    }
}

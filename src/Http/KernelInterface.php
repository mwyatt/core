<?php

namespace Mwyatt\Core\Http;

interface KernelInterface
{
    public function __construct();
    public function getService($key);
    public function setRoutes(array $routes);
    public function setMiddleware($config);
    public function setSettings(array $settings);
    public function setServicesOptional();
    public function setServices($path);
    public function setServiceProjectPath($projectPath);
    public function setServicesEssential();
    public function route();
}

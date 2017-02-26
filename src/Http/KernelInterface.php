<?php

namespace Mwyatt\Core\Http;

interface KernelInterface
{
    public function __construct();
    public function getService($key);
    public function setMiddleware(array $config);
    public function setMiddlewarePost(array $config);
    public function setSettings(array $settings);
    public function setServicesOptional(array $keys = []);
    public function setServices($path);
    public function setServiceProjectPath($projectPath);
    public function setServicesEssential();
    public function route();
}

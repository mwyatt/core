<?php
namespace Mwyatt\Core;

interface CacheInterface
{
	public function __construct($path = '');
	public function setPathBase($path);
	public function getPathBase($append = '');
	public function create($fileName, $data);
	public function read($fileName);
	public function delete($fileName);
}

<?php

namespace Mwyatt\Core;

interface UrlInterface
{
	public function __construct($host, $installPathQuery, $install = '');
	public function getPath();
	public function getQueryArray();
	public function getRoutes();
	public function setRoutes(\Pux\Mux $mux);
	public function generate($key = '', $config = []);
	public function generateVersioned($pathBase, $pathAppend);
	public function jsonSerialize();
}

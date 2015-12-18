<?php
namespace Mwyatt\Core;

interface CacheInterface
{
	public function getKey();
	public function setKey($key);
	public function getData();


	/**
	 * set the key to work with when caching data
	 * @param string $key path/foo-bar
	 */
	public function __construct($key);


	/**
	 * serialises and creates cache file if required
	 * if the file already exists, skip this
	 * @param  string $key  example-file-name
	 * @param  array $data 
	 * @return bool       
	 */
	public function create($data);


	/**
	 * reads in the cached file, if it exists
	 * unserialises and stores in data property
	 * @param  string $key example-file-name
	 * @return bool      
	 */
	public function read();


	/**
	 * removes the file from the cache
	 * @param  string $key 
	 * @return bool      
	 */
	public function delete();
}

<?php

namespace Mwyatt\Core;

/**
 * will concern itself with large collections of objects and or arrays
 * these will be easily accessible by storing in files '-' delimiter
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Cache extends \Mwyatt\Core\Data implements \Mwyatt\Core\CacheInterface
{


	/**
	 * folder house for cache
	 * @var string
	 */
	protected $path = 'cache/';


	protected $key = '';


	protected $pathBase;


	/**
	 * typical extension used
	 * @var string
	 */
	protected $extension = '';


	public function __construct()
	{
        $this->pathBase = (string) (__DIR__ . '/../');
	}


	/**
	 * returns the full path for a cached item regardless if it exists
	 * @param  string $key this-delimiter-space
	 * @return string      
	 */
	protected function getPath($key) {
		return $this->pathBase . $this->path . $key . $this->extension;
	}


	/**
	 * serialises and creates cache file if required
	 * if the file already exists, skip this
	 * @param  string $key  example-file-name
	 * @param  array $data 
	 * @return bool       
	 */
	public function create($data, $key = '')
	{
		if ($key) {
			$this->setKey($key);
		}

		// file must not already exist
		if (file_exists($this->getPath($this->getKey()))) {
			return;
		}

		// stringify
		$data = serialize($data);

		// write to file
		if (file_put_contents($this->getPath($this->getKey()), $data)) {
			return true;
		}
	}


	protected function getKey()
	{
		return $this->key;
	}


	/**
	 * reads in the cached file, if it exists
	 * unserialises and stores in data property
	 * @param  string $key example-file-name
	 * @return bool      
	 */
	public function read($key)
	{

		// store attempted key for create function
		$this->key = $key;

		// quickly check if a file exists
		if (! file_exists($this->getPath($key))) {
			return;
		}

		// load in
		$data = file_get_contents($this->getPath($key));
		return $this->setData(unserialize($data));
	}


	/**
	 * removes the file from the cache
	 * @param  string $key 
	 * @return bool      
	 */
	public function delete($key)
	{
		
		// nothing to delete
		if (! file_exists($this->getPath($key))) {
			return;
		}

		// remove
		if (unlink($this->getPath($key))) {
			return true;
		}
	}


	public function setKey($key)
	{
		return $this->key = $key;
	}
} 

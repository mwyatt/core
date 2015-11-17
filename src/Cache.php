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


	/**
	 * the filepath and name for the file of focus
	 * @var string
	 */
	protected $key = '';


	/**
	 * the base path for this package
	 * @var string
	 */
	protected $pathBase;


	/**
	 * typical extension used
	 * @var string
	 */
	protected $extension = '';


	/**
	 * set the key to work with when caching data
	 * @param string $key path/foo-bar
	 */
	public function __construct($key)
	{
        $this->pathBase = (string) (__DIR__ . '/../');
        $this->setKey($key);
	}


	/**
	 * returns the full path for a cached item regardless if it exists
	 * @param  string $key this-delimiter-space
	 * @return string      
	 */
	protected function getFilePath($key) {
		return $this->pathBase . $this->path . $this->getKey() . $this->extension;
	}


	/**
	 * serialises and creates cache file if required
	 * if the file already exists, skip this
	 * @param  string $key  example-file-name
	 * @param  array $data 
	 * @return bool       
	 */
	public function create($data)
	{
		$key = $this->getKey();
		$path = $this->getFilePath();

		// file must not already exist
		if (file_exists($path)) {
			return;
		}

		// stringify
		$data = serialize($data);

		// write to file
		if (file_put_contents($path, $data)) {
			return true;
		}
	}


	public function getKey()
	{
		return $this->key;
	}


	/**
	 * reads in the cached file, if it exists
	 * unserialises and stores in data property
	 * @param  string $key example-file-name
	 * @return bool      
	 */
	public function read()
	{
		$key = $this->getKey();
		$path = $this->getFilePath();

		// quickly check if a file exists
		if (! file_exists($path)) {
			return;
		}

		// load in
		$data = file_get_contents($path);
		return $this->setData(unserialize($data));
	}


	/**
	 * removes the file from the cache
	 * @param  string $key 
	 * @return bool      
	 */
	public function delete()
	{
		$key = $this->getKey();
		$path = $this->getFilePath();

		// nothing to delete
		if (! file_exists($this->getFilePath())) {
			return;
		}

		// remove
		if (unlink($this->getFilePath())) {
			return true;
		}
	}


	/**
	 * empty entire cache directory
	 * @return bool 
	 */
	public function flush()
	{
		// glob(pattern)
	}


	public function setKey($key)
	{
		return $this->key = $key;
	}
}

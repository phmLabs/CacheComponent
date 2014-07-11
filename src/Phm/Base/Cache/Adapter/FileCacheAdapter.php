<?php
namespace Phm\Base\Cache\Adapter;

use Phm\Base\Cache\KeyNotFoundException;

class FileCacheAdapter implements CacheAdapter
{

    private $cacheDirectory;

    public function __construct($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    public function get($key)
    {
        $filename = $this->cacheDirectory . DIRECTORY_SEPARATOR . $key . ".cache";
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }else{
            throw new KeyNotFoundException("The key '".$key."' is not set.");
        }
    }

    public function set($key, $value, $ttl)
    {
        $filename = $this->cacheDirectory . DIRECTORY_SEPARATOR . $key . ".cache";
        file_put_contents($filename, $value);
    }

    public function remove($key)
    {
      unlink($this->cacheDirectory . DIRECTORY_SEPARATOR . $key . ".cache");
    }
}
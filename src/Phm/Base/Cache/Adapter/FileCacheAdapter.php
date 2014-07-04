<?php
namespace Phm\Base\Cache\Adapter;

use Phm\Base\Cache\CacheItem;

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
        }
        return null;
    }

    public function set($key, $value, $ttl)
    {
        $filename = $this->cacheDirectory . DIRECTORY_SEPARATOR . $key . ".cache";
        file_put_contents($filename, $value);
    }
}
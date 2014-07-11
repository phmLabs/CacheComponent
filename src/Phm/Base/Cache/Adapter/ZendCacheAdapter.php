<?php
namespace Phm\Base\Cache\Adapter;

class ZendCacheAdapter implements CacheAdapter
{

    private $zendStorage;

    public function __construct(\Zend\Cache\Storage\StorageInterface $storage)
    {
        $this->zendStorage = $storage;
    }

    public function remove($key) {

    }

    public function get($key)
    {
        return $this->zendStorage->getItem($key);
    }

    public function set($key, $value, $ttl)
    {
        $this->zendStorage->getOptions()->setTtl($ttl);
        return $this->zendStorage->setItem($key, $value);
    }
}
<?php
namespace Phm\Base\Cache\Adapter;

interface CacheAdapter
{

    public function get($key);

    public function set($key, $value, $ttl);
    
    public function remove($key);
}
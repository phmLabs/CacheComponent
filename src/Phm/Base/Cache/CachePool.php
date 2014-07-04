<?php
namespace Phm\Base\Cache;

use Phm\Base\Cache\Adapter\CacheAdapter;

class CachePool
{

    private $cacheAdapter;

    private $standardOptions;

    public function __construct(CacheAdapter $cacheAdapter, CacheOptions $standardCacheOptions = null)
    {
        $this->cacheAdapter = $cacheAdapter;
        if (! is_null($standardCacheOptions)) {
            $this->standardOptions = $standardCacheOptions;
        } else {
            $this->standardOptions = new CacheOptions();
        }
    }

    private function getCacheItem($key)
    {
        $plainCacheItem = $this->cacheAdapter->get($key);
        if (is_null($plainCacheItem)) {
            return new CacheItem(null, time() - 1);
        }
        return unserialize($plainCacheItem);
    }

    private function setCacheItem($key, $value, $ttl, CacheOptions $cacheOptions)
    {
        $cacheTtl = $ttl + $cacheOptions->getStaleTime();
        $cacheItem = new CacheItem($value, time() + $ttl);
        $this->cacheAdapter->set($key, serialize($cacheItem), $cacheTtl);
    }

    public function get($key, $ttl, callable $callable = null, CacheOptions $cacheOptions = null)
    {
        if (! is_null($cacheOptions)) {
            $mergedCacheOptions = $this->standardOptions->merge($cacheOptions);
        } else {
            $mergedCacheOptions = $this->standardOptions;
        }
        
        $cacheItem = $this->getCacheItem($key);
        
        if ($cacheItem->isValid()) {
            return $cacheItem->getValue();
        } else {
            if (! is_null($callable)) {
                try {
                    $value = $callable();
                    $this->setCacheItem($key, $value, $ttl, $mergedCacheOptions);
                } catch (\Exception $e) {
                    if ($mergedCacheOptions->isStaleOnError()) {
                        $value = $cacheItem->getValue();
                        if (is_null($value)) {
                            throw $e;
                        }
                    }
                }
                return $value;
            }
        }
        
        return $cacheItem;
    }
}
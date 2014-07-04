<?php
namespace Phm\Base\Cache;

use Phm\Base\Cache\Adapter\CacheAdapter;

/**
 * This class represents the cache pool and can be used to cache all kind of
 * information.
 *
 * @author Nils Langner <nils.langner@phmlabs.com>
 *        
 */
class CachePool implements PoolInterface
{

    /**
     * The cache adapter that connects to the physical caching system.
     *
     * @var CacheAdapter
     */
    private $cacheAdapter;

    /**
     * These are the standard cache options that can be provided via
     * constructor.
     *
     * @var CacheOptions
     */
    private $standardOptions;

    public function removeItem($key)
    {
        $this->cacheAdapter->remove($key);
    }

    /**
     *
     * @param string $key
     *            The cache key
     * @param int $ttl
     *            time to live in seconds
     * @param callable $callable
     *            the callable that provides the cache entry if there is no
     *            entry in the current cache pool.
     * @param CacheOptions $cacheOptions
     *            The cache options that will overwrite the standard cache
     *            options set in the constructor
     * @throws Exception
     * @return mixed
     */
    public function getItem($key, $ttl = 0, callable $callable = null, CacheOptions $cacheOptions = null)
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
            }else{
                throw new KeyNotFoundException("The key '".$key."' was not found in the cache.");
            }
        }
    }

    /**
     * The conctructor.
     *
     * @param CacheAdapter $cacheAdapter
     *            The caching system you want to connect with this pool.
     * @param CacheOptions $standardCacheOptions
     *            The standard cache options that will be used if they are not
     *            overwritten in the get command.
     */
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
        try {
            $plainCacheItem = $this->cacheAdapter->get($key);
            return unserialize($plainCacheItem);
        } catch (KeyNotFoundException $e) {
            return new CacheItem(null, 0, false);
        }
    }

    private function setCacheItem($key, $value, $ttl, CacheOptions $cacheOptions)
    {
        $cacheTtl = $ttl + $cacheOptions->getStaleTime();
        $cacheItem = new CacheItem($value, time() + $ttl);
        $this->cacheAdapter->set($key, serialize($cacheItem), $cacheTtl);
    }
}
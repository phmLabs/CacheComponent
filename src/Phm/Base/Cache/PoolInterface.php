<?php
namespace Phm\Base\Cache;

/**
 * This class represents the cache pool and can be used to cache all kind of
 * information.
 *
 * @author Nils Langner <nils.langner@phmlabs.com>
 *        
 */
interface PoolInterface
{
    /**
     * Removes an item from the cache.
     * 
     * @param string $key
     */
    public function removeItem($key);

    /**
     * This function is used to retrieve data from the cache. If the data is not set this function is able
     * to execute a callable (e.g. Closure) for calculating the value.
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
     * @throws \Exception
     * @return mixed
     */
    public function getItem($key, $ttl = 0, callable $callable = null, CacheOptions $cacheOptions = null);
}
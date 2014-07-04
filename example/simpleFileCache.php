<?php

include_once __DIR__ . '/../src/autoload.php';

use Phm\Base\Cache\CacheOptions;
use Phm\Base\Cache\Adapter\FileCacheAdapter;
use Phm\Base\Cache\CachePool;

$standardCacheOptions = new CacheOptions();
$standardCacheOptions->setStaleTime(600);
$standardCacheOptions->setStaleIfError(true);

$cachePool = new CachePool(new FileCacheAdapter("/tmp/cache"), $standardCacheOptions);

$calculateMyVar = function ()
{
    return date("Y-m-d H:i:s");
};

$cacheItem = $cachePool->get("myVar", 60, $calculateMyVar);

var_dump($cacheItem);
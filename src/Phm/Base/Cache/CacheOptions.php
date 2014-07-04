<?php
namespace Phm\Base\Cache;

class CacheOptions
{

    private $staleTime = null;

    private $isStaleIfError = false;

    private $isStaleOnRevalidate = false;

    public function setStaleTime($staleWhileRevalidate)
    {
        $this->staleWhileRevalidate = $staleWhileRevalidate;
    }

    public function getStaleTime()
    {
        return $this->staleWhileRevalidate;
    }

    public function setStaleIfError($isStaleOnError)
    {
        $this->isStaleIfError = $isStaleOnError;
    }

    public function getStaleIfError()
    {
        return $this->isStaleIfnError;
    }

    public function setIsStaleOnRevalidate($isStaleOnRevalidate)
    {
        $this->isStaleOnRevalidate = $isStaleOnRevalidate;
    }

    public function getIsStaleOnRevalidate()
    {
        return $this->isStaleOnRevalidate;
    }

    public function merge(CacheOptions $cacheOptions)
    {
        // @error merge function not updated when code was updated
        $mergedOptions = clone ($this);
        
        $newStaleIfErrorTime = $cacheOptions->getStaleIfErrorTime();
        if (! is_null($newStaleIfErrorTime)) {
            $mergedOptions->setStaleIfErrorTime($newStaleIfErrorTime);
        }
        
        $staleWhileRevalidateTime = $cacheOptions->getStaleWhileRevalidateTime();
        if (! is_null($newStaleIfErrorTime)) {
            $mergedOptions->setStaleWhileRevalidateTime($staleWhileRevalidateTime);
        }
        
        return $mergedOptions;
    }
}
<?php
namespace Phm\Base\Cache;

class CacheOptions
{

    private $staleTime = null;

    private $isStaleIfError = false;

    private $isStaleOnRevalidate = false;

    public function setStaleTime($staleTime)
    {
        $this->staleTime = $staleTime;
    }

    public function getStaleTime()
    {
        return $this->staleTime;
    }

    public function setStaleIfError($isStaleOnError)
    {
        $this->isStaleIfError = $isStaleOnError;
    }

    public function getStaleIfError()
    {
        return $this->isStaleIfError;
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
        $mergedOptions = clone ($this);
        
        $newStaleTime = $cacheOptions->getStaleTime();
        if (! is_null($newStaleTime)) {
            $mergedOptions->setStaleTime($newStaleTime);
        }
        
        return $mergedOptions;
    }
}
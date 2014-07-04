<?php
namespace Phm\Base\Cache;

class CacheItem
{
    private $value;

    private $expireDate;
    
    private $isHit;
    
    public function __construct($value, $expireDate, $isHit = true)
    {
        $this->expireDate = $expireDate;
        $this->value = $value;
        $this->isHit = $isHit;
    }

    public function getValue()
    {
        if( !$this->isHit() ) {
            throw new KeyNotFoundException();
        }
        return $this->value;
    }

    public function isHit()
    {
        return $this->isHit;
    }
    
    public function isValid()
    {
        if ($this->expireDate >= time()) {
            return true;
        } else {
            return false;
        }
    }
}
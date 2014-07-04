<?php
namespace Phm\Base\Cache;

class CacheItem
{

    private $value;

    private $expireDate;

    public function __construct($value, $expireDate)
    {
        $this->expireDate = $expireDate;
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
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
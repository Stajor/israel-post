<?php namespace IsraelPost;

use Psr\Cache\CacheItemPoolInterface;

class IsraelPost {
    private ?CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $cache = null) {
        $this->cache = $cache;
    }

    public function deliveryTracking(): DeliveryTracking {
        return new DeliveryTracking($this->cache);
    }

    public function deliveryRate(): DeliveryRate {
        return new DeliveryRate($this->cache);
    }
}

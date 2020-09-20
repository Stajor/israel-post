<?php namespace IsraelPost;

use IsraelPost\DeliveryRate\DeliveryRateAbroad;
use Psr\Cache\CacheItemPoolInterface;

class DeliveryRate {
    protected ?CacheItemPoolInterface $cache;

    public function __construct(?CacheItemPoolInterface $cache) {
        $this->cache = $cache;
    }

    /**
     * משלוח דואר לחו"ל
     * @return DeliveryRateAbroad
     */
    public function abroad(): DeliveryRateAbroad {
        return new DeliveryRateAbroad($this->cache);
    }
}

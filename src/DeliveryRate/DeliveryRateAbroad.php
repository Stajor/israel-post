<?php namespace IsraelPost\DeliveryRate;

use Psr\Cache\CacheItemPoolInterface;

class DeliveryRateAbroad {
    protected ?CacheItemPoolInterface $cache;

    public function __construct(?CacheItemPoolInterface $cache) {
        $this->cache = $cache;
    }

    /**
     * צרור קטן
     * @return DeliveryRateAbroadLittleBundle
     */
    public function littleBundle(): DeliveryRateAbroadLittleBundle {
        return new DeliveryRateAbroadLittleBundle($this->cache);
    }

    /**
     * מכתב
     * @return DeliveryRateAbroadLetter
     */
    public function letter(): DeliveryRateAbroadLetter {
        return new DeliveryRateAbroadLetter($this->cache);
    }

    /**
     * גלוייה
     * @return DeliveryRateAbroadPostcard
     */
    public function postcard(): DeliveryRateAbroadPostcard {
        return new DeliveryRateAbroadPostcard($this->cache);
    }
}

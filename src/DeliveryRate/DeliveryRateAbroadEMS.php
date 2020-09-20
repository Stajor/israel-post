<?php namespace IsraelPost\DeliveryRate;

use IsraelPost\IsraelPostCountryNotFoundException;
use IsraelPost\IsraelPostException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class DeliveryRateAbroadEMS extends DeliveryRateAbstract {
    public function __construct(?CacheItemPoolInterface $cache) {
        $this->countries = json_decode(file_get_contents(dirname(__DIR__).'/data/countries-CountriesComboEMS.json'), true);

        parent::__construct($cache);
    }

    /**
     * דואר מהיר
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws IsraelPostException
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     */
    public function expressMailDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~EMS', 'דואר מהיר - EMS~{country}', $amount, $weight);
    }
}

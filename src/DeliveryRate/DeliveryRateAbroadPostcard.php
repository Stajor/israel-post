<?php namespace IsraelPost\DeliveryRate;

use IsraelPost\IsraelPostCountryNotFoundException;
use IsraelPost\IsraelPostException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class DeliveryRateAbroadPostcard extends DeliveryRateAbstract {
    public function __construct(?CacheItemPoolInterface $cache) {
        $this->countries = json_decode(file_get_contents(dirname(__DIR__).'/data/countries-CountriesCombo.json'), true);

        parent::__construct($cache);
    }

    /**
     * דואר אוויר
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws IsraelPostException
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     */
    public function airDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~גלוייה', 'דואר אויר~{country}', $amount, $weight);
    }


    /**
     * דואר ים ויבשה
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     * @throws IsraelPostException
     */
    public function seaAndLandDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~גלוייה', 'דואר ים ויבשה~{country}', $amount, $weight);
    }
}

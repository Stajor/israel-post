<?php namespace IsraelPost\DeliveryRate;

use IsraelPost\IsraelPostCountryNotFoundException;
use IsraelPost\IsraelPostException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class DeliveryRateAbroadLetter extends DeliveryRateAbstract {
    public function __construct(?CacheItemPoolInterface $cache) {
        $this->countries = json_decode(file_get_contents('./src/data/countries-CountriesCombo.json'), true);

        parent::__construct($cache);
    }

    /**
     * דואר אוויר משלוח רגיל
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws IsraelPostException
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     */
    public function standardAirDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~מכתב', 'דואר אויר~משלוח רגיל~{country}', $amount, $weight);
    }

    /**
     * דואר אוויר משלוח רשום
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws InvalidArgumentException
     * @throws IsraelPostException
     * @throws IsraelPostCountryNotFoundException
     */
    public function registeredAirDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~מכתב', 'דואר אויר~רשום~{country}', $amount, $weight);
    }

    /**
     * דואר ים ויבשה משלוח רגיל
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     * @throws IsraelPostException
     */
    public function standardSeaAndLandDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~מכתב', 'דואר ים ויבשה~משלוח רגיל~{country}', $amount, $weight);
    }

    /**
     * דואר ים ויבשה משלוח רשום
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     * @throws IsraelPostException
     */
    public function registeredSeaAndLandDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~מכתב', 'דואר ים ויבשה~רשום~{country}', $amount, $weight);
    }

    /**
     * דואר מהיר - EMS
     *
     * @param string $countryCode
     * @param int $amount
     * @param int $weight
     * @return float
     * @throws InvalidArgumentException
     * @throws IsraelPostCountryNotFoundException
     * @throws IsraelPostException
     */
    public function emsDeliveryRate(string $countryCode, int $amount, int $weight): float {
        return $this->rate($countryCode, 'משלוח דואר לחו"ל~מכתב', 'דואר מהיר - EMS~{country}', $amount, $weight);
    }
}

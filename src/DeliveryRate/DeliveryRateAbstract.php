<?php namespace IsraelPost\DeliveryRate;

use Exception;
use IsraelPost\IsraelPostCountryNotFoundException;
use IsraelPost\IsraelPostException;
use IsraelPost\ServiceAbstract;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

abstract class DeliveryRateAbstract extends ServiceAbstract {
    const API_URL = 'https://www.israelpost.co.il';

    public function __construct(?CacheItemPoolInterface $cache) {
        $this->cache = $cache;

        parent::__construct(self::API_URL);
    }

    protected array $countries;

    /**
     * @param string $countryCode
     * @return array
     * @throws IsraelPostCountryNotFoundException
     */
    protected function country(string $countryCode): array {
        $countryCode = strtoupper($countryCode);

        if (!isset($this->countries[$countryCode])) {
            throw new IsraelPostCountryNotFoundException("Country by code {$countryCode} not found");
        }

        return $this->countries[$countryCode];
    }

    /**
     * @param string $countryCode
     * @param string $menu
     * @param string $option
     * @param int $amount
     * @param int $weight
     * @return mixed
     * @throws IsraelPostCountryNotFoundException
     * @throws IsraelPostException
     * @throws InvalidArgumentException
     */
    protected function rate(string $countryCode, string $menu, string $option, int $amount, int $weight) {
        $country    = $this->country($countryCode);
        $option     = str_replace('{country}', 'C'.$country['value'], $option);
        $key        = __CLASS__.".{$countryCode}.{$menu}.{$option}";

        $rates = $this->cached($key, function() use ($country, $menu, $option) {
            $this->request('/npostcalc.nsf/calculator2');

            $html = $this->request('/npostcalc.nsf/CalcPrice', [
                'openagent'     => '',
                'lang'          => 'HE',
                'menuChosen'    => $menu,
                'serviceoption' => $option,
                'qty'           => '',
                'shipqty'       => 0,
                'weight'        => '',
                'cname'         => $country['country'],
                '_'             => time() * 1000
            ]);

            try {
                $rows = json_decode($html, true, 512, JSON_THROW_ON_ERROR);
            } catch (Exception $e) {
                throw new IsraelPostException($e->getMessage(), $e->getCode(), $e);
            }

            $data = [];

            foreach ($rows['prices'] AS $price) {
                $weight     = $this->convertWeight($price['Pweight']);
                $quantity   = $this->convertQuantity($price['Pqty']);

                if (!isset($data["{$weight[0]}-{$weight[1]}"])) {
                    $data["{$weight[0]}-{$weight[1]}"]['weight'] = $weight;
                    $data["{$weight[0]}-{$weight[1]}"]['prices'] = [];
                }

                $data["{$weight[0]}-{$weight[1]}"]['prices'][] = [
                    'amount'    => $quantity,
                    'price'     => (float)$price['Pprice'],
                    'comments'  => array_map(fn($comment) => (int)$comment['cno'], $price['comments'] ?? [])
                ];
            }

            return array_values($data);
        });

        $rate = null;

        foreach ($rates AS $row) {
            if ($weight >= $row['weight'][0] && $weight < $row['weight'][1]) {
                foreach ($row['prices'] AS $price) {
                    if ($price['amount'][0] == 0 && $price['amount'][1] == 0) {
                        return $price['price'] * $amount;
                    } elseif ($amount >= $price['amount'][0] && $amount <= $price['amount'][1]) {
                        return $price['price'];
                    }
                }
            }
        }

        throw new IsraelPostException("No rate found for country {$countryCode}");
    }

    private function convertQuantity(string $quantity) {
        if (strpos($quantity, 'מעל') !== false) {
            $from = $quantity;
            $to = 1000;
        } elseif ($quantity == 'כל כמות') {
            $from = null;
            $to = null;
        } elseif (strpos($quantity, '-') === false) {
            $from = 0;
            $to = (int)$quantity;
        } else {
            list($to, $from) = explode(' - ', $quantity);
        }

        return [(int)preg_replace("/[^0-9]/", '', $from), (int)preg_replace("/[^0-9]/", '', $to)];
    }

    private function convertWeight(string $weight) {
        $from       = '0';
        $to         = '0';
        $fromFactor = 1;
        $toFactor   = 1;

        if (strpos($weight, 'עד') !== false) {
            $to = preg_replace("/[^0-9]/", '', $weight);
        } elseif($weight == '--') {
            $to = 1000;
        } elseif (strpos($weight, ' - ') !== false) {
            list($to, $from) = explode(' - ', $weight);
        }

        if (strpos($from, 'ק"ג') !== false) {
            $fromFactor = 1000;
        }

        if (strpos($to, 'ק"ג') !== false || $fromFactor == 1000) {
            $toFactor = 1000;
        }

        return [preg_replace("/[^0-9.]/", '', $from) * $fromFactor, preg_replace("/[^0-9.]/", '', $to) * $toFactor];
    }
}

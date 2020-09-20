<?php namespace IsraelPost;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * Class DeliveryTracking
 * @package IsraelPost
 * @url
 */
class DeliveryTracking extends ServiceAbstract {
    const API_URL = 'https://mypost.israelpost.co.il';

    public function __construct(?CacheItemPoolInterface $cache) {
        $this->cache = $cache;

        parent::__construct(self::API_URL);
    }

    /**
     * Tracking is available for all items shipped by either domestic or international service, shipped to or from Israel
     *
     * @param string $itemCode
     * @return stdClass
     * @throws IsraelPostException
     * @throws InvalidArgumentException
     */
    public function track(string $itemCode): stdClass {
        return $this->cached(__CLASS__.'.'.$itemCode, function() use ($itemCode) {
            $response   = $this->request('/itemtrace');
            $crawler    = new Crawler($response);
            $token      = $crawler->filter('[name="__RequestVerificationToken"]')->first()->attr('value');

            preg_match('/var lcid=(.*)/m', $response, $matches);

            $response = $this->request('/umbraco/Surface/ItemTrace/GetItemTrace', [
                'itemCode' => $itemCode,
                'lcid' => $matches[1],
                '__RequestVerificationToken' => $token
            ], 'POST', ['headers' => [
                'x-requested-with' => 'XMLHttpRequest',
                'referer' => self::API_URL.'/itemtrace'
            ]]);

            try {
                $data = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
            } catch (Throwable $e) {
                throw new IsraelPostException($e->getMessage(), $e->getCode(), $e);
            }

            if ($data->ReturnCode != 0) {
                throw new IsraelPostException($data->ErrorDescription, $data->ReturnCode);
            }

            return $data;
        });
    }
}

<?php namespace IsraelPost;

use Closure;
use GuzzleHttp\Client;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Throwable;

abstract class ServiceAbstract {
    protected ?CacheItemPoolInterface $cache;
    private Client $client;

    public function __construct(string $baseUrl) {
        $this->client = new Client(['cookies' => true, 'base_uri' => $baseUrl]);
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $method
     * @param array $options
     * @return string
     * @throws IsraelPostException
     */
    protected function request(string $url, array $data = [], string $method = 'GET', array $options = []): string {
        $options['verify'] = false;

        switch ($method) {
            case 'POST':
                $options['form_params'] = $data;
                break;

            case 'GET':
                $options['query'] = $data;
                break;
        }

        try {
            $response = (string)$this->client->request($method, $url, $options)->getBody();
        } catch(Throwable $e) {
            throw new IsraelPostException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * @param string $key
     * @param Closure $closure
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function cached(string $key, Closure $closure) {
        $key = str_replace('\\', '.', $key);

        if (is_null($this->cache)) {
            return $closure();
        } else {
            $item = $this->cache->getItem($key);

            if (!$item->isHit()) {
                $data = $closure();
                $item->set($data);
                $this->cache->save($item);
            }

            return $item->get();
        }
    }
}

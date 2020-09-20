<?php

use IsraelPost\DeliveryRate\DeliveryRateAbroadEMS;
use IsraelPost\IsraelPost;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DeliveryRateAbroadEMSTest extends TestCase {
    private DeliveryRateAbroadEMS $service;
    private int $amount = 26;
    private int $weight = 252;

    protected function setUp(): void {
        $israelPost = new IsraelPost(new ArrayAdapter());
        $this->service = $israelPost->deliveryRate()->abroad()->ems();
    }

    public function testService() {
        $this->assertInstanceOf(DeliveryRateAbroadEMS::class, $this->service);
    }

    public function testExpressMailDeliveryRate() {
        $rate   = $this->service->expressMailDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->expressMailDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }
}

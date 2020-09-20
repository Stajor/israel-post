<?php

use IsraelPost\DeliveryRate\DeliveryRateAbroadPostcard;
use IsraelPost\IsraelPost;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DeliveryRateAbroadPostcardTest extends TestCase {
    private DeliveryRateAbroadPostcard $service;
    private int $amount = 26;
    private int $weight = 200;

    protected function setUp(): void {
        $israelPost = new IsraelPost(new ArrayAdapter());
        $this->service = $israelPost->deliveryRate()->abroad()->postcard();
    }

    public function testService() {
        $this->assertInstanceOf(DeliveryRateAbroadPostcard::class, $this->service);
    }

    public function testAirDeliveryRate() {
        $rate   = $this->service->airDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->airDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }

    public function testSeaAndLandDeliveryRate() {
        $rate = $this->service->seaAndLandDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->seaAndLandDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }
}

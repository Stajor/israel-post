<?php

use IsraelPost\DeliveryRate\DeliveryRateAbroadLittleBundle;
use IsraelPost\IsraelPost;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DeliveryRateAbroadLittleBundleTest extends TestCase {
    private DeliveryRateAbroadLittleBundle $service;
    private int $amount = 26;
    private int $weight = 200;

    protected function setUp(): void {
        $israelPost = new IsraelPost(new ArrayAdapter());
        $this->service = $israelPost->deliveryRate()->abroad()->littleBundle();
    }

    public function testService() {
        $this->assertInstanceOf(DeliveryRateAbroadLittleBundle::class, $this->service);
    }

    public function testStandardAirDeliveryRate() {
        $rate   = $this->service->standardAirDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->standardAirDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }

    public function testRegisteredAirDeliveryRate() {
        $rate = $this->service->registeredAirDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->registeredAirDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }

    public function testStandardSeaAndLandDeliveryRate() {
        $rate = $this->service->standardSeaAndLandDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->standardSeaAndLandDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }

    public function testRegisteredSeaAndLandDeliveryRate() {
        $rate = $this->service->registeredSeaAndLandDeliveryRate('RU', $this->amount, $this->weight);
        $rateX2 = $this->service->registeredSeaAndLandDeliveryRate('RU', $this->amount * 2, $this->weight * 2);

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertGreaterThan($rate, $rateX2);
    }
}

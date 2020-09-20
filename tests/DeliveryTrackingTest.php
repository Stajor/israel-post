<?php
use IsraelPost\IsraelPost;
use IsraelPost\IsraelPostException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DeliveryTrackingTest extends TestCase {
    public function testTrack() {
        $israelPost = new IsraelPost();
        $response = $israelPost->deliveryTracking()->track('EE123456789IL');

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('ReturnCode', $response);
        $this->assertEquals(0, $response->ReturnCode);
    }

    public function testInvalidCode() {
        $this->expectException(IsraelPostException::class);

        $israelPost = new IsraelPost();
        $israelPost->deliveryTracking()->track('wrongcode');
    }

    public function testCachedTrack() {
        $cache          = new ArrayAdapter();
        $response       = (new IsraelPost())->deliveryTracking()->track('EE123456789IL');
        $cachedResponse = (new IsraelPost($cache))->deliveryTracking()->track('EE123456789IL');

        $this->assertEquals($response, $cachedResponse);
    }
}

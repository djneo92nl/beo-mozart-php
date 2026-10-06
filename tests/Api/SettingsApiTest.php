<?php

namespace Djneo92nl\BeoMozart\Tests\Api;

use Djneo92nl\BeoMozart\Api\BluetoothApi;
use Djneo92nl\BeoMozart\Api\ProductApi;
use Djneo92nl\BeoMozart\Api\SoundApi;
use Djneo92nl\BeoMozart\Tests\FakeHttpTransport;
use PHPUnit\Framework\TestCase;

class SettingsApiTest extends TestCase
{
    public function test_adjustments_come_from_the_product_state(): void
    {
        $transport = new FakeHttpTransport;
        $transport->response = ['soundSettings' => ['adjustments' => ['bass' => 2, 'treble' => -1, 'loudness' => true]]];

        $this->assertSame(['bass' => 2, 'treble' => -1, 'loudness' => true], (new SoundApi($transport))->getAdjustments());
        $this->assertSame('/api/v1/state', $transport->lastCall()['path']);
    }

    public function test_adjustments_are_null_when_the_device_does_not_answer(): void
    {
        $transport = new FakeHttpTransport;
        $transport->response = null;

        $this->assertNull((new SoundApi($transport))->getAdjustments());
    }

    public function test_features_are_read_from_sound_features(): void
    {
        $transport = new FakeHttpTransport;
        (new SoundApi($transport))->getFeatures();

        $this->assertSame(['method' => 'GET', 'path' => '/api/v1/sound/features', 'body' => [], 'query' => []], $transport->lastCall());
    }

    public function test_bass_treble_and_loudness_put_a_value_body(): void
    {
        $transport = new FakeHttpTransport;
        $api = new SoundApi($transport);

        $api->setBass(3);
        $api->setTreble(-2);
        $api->setLoudness(false);

        $this->assertSame([
            ['method' => 'PUT', 'path' => '/api/v1/sound/settings/adjustments/bass', 'body' => ['value' => 3], 'query' => []],
            ['method' => 'PUT', 'path' => '/api/v1/sound/settings/adjustments/treble', 'body' => ['value' => -2], 'query' => []],
            ['method' => 'PUT', 'path' => '/api/v1/sound/settings/adjustments/loudness', 'body' => ['value' => false], 'query' => []],
        ], $transport->calls);
    }

    public function test_friendly_name_is_put(): void
    {
        $transport = new FakeHttpTransport;
        (new ProductApi($transport))->setFriendlyName('Kitchen');

        $this->assertSame(['method' => 'PUT', 'path' => '/api/v1/product/info/friendlyname', 'body' => ['friendlyName' => 'Kitchen'], 'query' => []], $transport->lastCall());
    }

    public function test_software_update_status_is_read(): void
    {
        $transport = new FakeHttpTransport;
        (new ProductApi($transport))->getSoftwareUpdate();

        $this->assertSame('/api/v1/softwareupdate', $transport->lastCall()['path']);
    }

    public function test_bluetooth_devices_are_read(): void
    {
        $transport = new FakeHttpTransport;
        (new BluetoothApi($transport))->getDevices();

        $this->assertSame(['method' => 'GET', 'path' => '/api/v1/setup/bluetooth/devices', 'body' => [], 'query' => []], $transport->lastCall());
    }
}

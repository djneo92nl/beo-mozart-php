<?php

namespace Djneo92nl\BeoMozart\Api;

use Djneo92nl\BeoMozart\Http\HttpTransportInterface;

class BluetoothApi
{
    public function __construct(protected HttpTransportInterface $transport) {}

    /**
     * Bluetooth devices the product knows: `items[]` with `address`, `name`,
     * `connected`. The spec has no way to pair, make discoverable or remove.
     * Null when the device did not answer.
     */
    public function getDevices(): ?array
    {
        return $this->transport->request('GET', '/api/v1/setup/bluetooth/devices');
    }
}

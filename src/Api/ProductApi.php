<?php

namespace Djneo92nl\BeoMozart\Api;

use Djneo92nl\BeoMozart\Http\HttpTransportInterface;

class ProductApi
{
    public function __construct(protected HttpTransportInterface $transport) {}

    /** The spec only has a setter: the current name is not readable over this API. */
    public function setFriendlyName(string $name): void
    {
        $this->transport->request('PUT', '/api/v1/product/info/friendlyname', ['friendlyName' => $name]);
    }

    /**
     * `softwareVersion`, `state`, `availableUpdate`, `updateType`, … Null when
     * the device did not answer.
     */
    public function getSoftwareUpdate(): ?array
    {
        return $this->transport->request('GET', '/api/v1/softwareupdate');
    }
}

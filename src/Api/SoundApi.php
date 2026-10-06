<?php

namespace Djneo92nl\BeoMozart\Api;

use Djneo92nl\BeoMozart\Http\HttpTransportInterface;

class SoundApi
{
    public function __construct(protected HttpTransportInterface $transport) {}

    /**
     * Current bass/treble/loudness (and the other adjustments the product has),
     * from the product state: `soundSettings.adjustments`. Null when the
     * device did not answer.
     */
    public function getAdjustments(): ?array
    {
        $state = $this->transport->request('GET', '/api/v1/state');

        return $state === null ? null : ($state['soundSettings']['adjustments'] ?? []);
    }

    /**
     * What this product supports and the allowed values, keyed by role
     * (e.g. standalone / multichannel). Null when the device did not answer.
     */
    public function getFeatures(): ?array
    {
        return $this->transport->request('GET', '/api/v1/sound/features');
    }

    public function setBass(int $value): void
    {
        $this->transport->request('PUT', '/api/v1/sound/settings/adjustments/bass', ['value' => $value]);
    }

    public function setTreble(int $value): void
    {
        $this->transport->request('PUT', '/api/v1/sound/settings/adjustments/treble', ['value' => $value]);
    }

    public function setLoudness(bool $value): void
    {
        $this->transport->request('PUT', '/api/v1/sound/settings/adjustments/loudness', ['value' => $value]);
    }
}

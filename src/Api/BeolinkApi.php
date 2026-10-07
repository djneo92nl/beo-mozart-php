<?php

namespace Djneo92nl\BeoMozart\Api;

use Djneo92nl\BeoMozart\Http\HttpTransportInterface;

class BeolinkApi
{
    public function __construct(protected HttpTransportInterface $transport) {}

    public function self(): ?array
    {
        return $this->transport->request('GET', '/api/v1/beolink/self');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function peers(): array
    {
        return $this->transport->request('GET', '/api/v1/beolink/peers') ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listeners(): array
    {
        return $this->transport->request('GET', '/api/v1/beolink/listeners') ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function availableListeners(): array
    {
        return $this->transport->request('GET', '/api/v1/beolink/available-listeners') ?? [];
    }

    public function join(string $jid, ?string $source = null): ?array
    {
        $query = $source !== null ? ['source' => $source] : [];

        return $this->transport->request('POST', "/api/v1/beolink/join/{$jid}", [], $query);
    }

    /** Host side of a join: expand this device's active experience to the peer with this JID. */
    public function expand(string $jid): void
    {
        $this->transport->request('POST', "/api/v1/beolink/expand/{$jid}");
    }

    /** Disconnect the peer with this JID from this device's active experience. */
    public function unexpand(string $jid): void
    {
        $this->transport->request('POST', "/api/v1/beolink/unexpand/{$jid}");
    }

    public function leave(): void
    {
        $this->transport->request('POST', '/api/v1/beolink/leave');
    }

    public function allStandby(): void
    {
        $this->transport->request('POST', '/api/v1/beolink/allstandby');
    }
}

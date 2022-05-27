<?php

namespace Payhook\Sdk;

use GuzzleHttp\Client;

class Payhook
{
    protected string $apiKey;

    protected array $options = [
        "url" => "https://api.payhook.org",
        "version" => "v1",
    ];

    protected Client $client;

    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->options = array_merge($this->options, $options);

        $this->client = new Client([
            'base_uri' => "{$this->options['url']}/{$this->options['version']}",
        ]);
    }

    public function invoke(string $method, array $params = [])
    {
        $response = $this->client->post("/invoke/{$method}", [
            'json' => $params,
            'headers' => [
                'X-Payhook-Api-Key' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['result'];
    }

    public function createPayment(array $params): array
    {
        $params = array_merge([
            'description' => '',
        ], $params);

        return $this->invoke('createPayment', $params);
    }

    public function deletePayment(int $id): void
    {
        $this->invoke('deletePayment', [
            'id' => $id,
        ]);
    }

    public function getPayment(int $id): array
    {
        return $this->invoke('getPayment', [
            'id' => $id,
        ]);
    }

    public function toNanos(float $amount): string
    {
        return $amount * 1e9;
    }

    public function fromNanos(string $nanos): float
    {
        $gmp = gmp_init($nanos);

        $left = gmp_div($gmp, 1e9);
        $right = gmp_mod($gmp, 1e9);

        return (float)"{$left}.{$right}";
    }

    public function isWebhookValid(string $id, string $event, array $data, string $signature): bool
    {
        $localSignature = $this->generateSignature($id, $event, $data);

        return hash_equals($localSignature, $signature);
    }

    public function generateSignature(string $id, string $event, array $data): string
    {
        $dataJson = json_encode($data, JSON_UNESCAPED_UNICODE);

        $signatureString = "id={$id}\nevent={$event}\ndata={$dataJson}";

        return hash_hmac('sha256', $signatureString, $this->apiKey);
    }
}
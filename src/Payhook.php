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

    /**
     * Converts money value to nanos.
     *
     * Eg: 100.43 -> 1004300000
     *
     * @param string $money
     * @return string
     */
    public static function moneyToNanos(string $money): string
    {
        // 1,431 -> 1.431
        $money = str_replace(',', '.', $money);

        // [0] => 1
        // [1] => 431
        $moneyParts = explode('.', $money);

        // 1
        $int = $moneyParts[0];

        // 431
        $dec = substr($moneyParts[1] ?? '0', 0, 9);

        // 1 431 000 000
        return "{$int}{$dec}" . str_repeat('0', max(9 - strlen($dec), 0));
    }

    /**
     * Convert nanos value to money.
     *
     * Eg: 1004300000 -> 100.43
     *
     * @param string $nanos
     * @return string
     */
    public static function nanosToMoney(string $nanos): string
    {
        $nanos = ltrim($nanos, '0');

        $dec = substr($nanos, strlen($nanos) - 9);
        if (strlen($dec) < 9) {
            $dec = str_repeat('0', 9 - strlen($dec)) . $dec;
        }
        
        $int = substr($nanos, 0, -strlen($dec));

        if (empty($int)) {
            $int = '0';
        }

        $dec = rtrim($dec, '0');

        $result = $int;

        if (!empty($dec)) {
            $result = $int . "." . $dec;
        }

        return $result;
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
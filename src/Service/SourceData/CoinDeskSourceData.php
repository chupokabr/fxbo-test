<?php

declare(strict_types=1);

namespace App\Service\SourceData;

use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Traversable;

final class CoinDeskSourceData implements SourceDataInterface
{
    private const BASE = 'BTC';
    private const QUOTE = 'USD';
    private const URL = 'https://api.coindesk.com/v1/bpi/historical/close.json';
    private const PROVIDER_NAME = 'coindesk';

    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $coinDesk
    )
    {
        $this->client = $coinDesk;
    }

    public function process(): Traversable
    {
        $data = $this->fetch();
        if (!isset($data['bpi'])) {
            throw new RuntimeException('no price index in rates');
        }
        $price = array_pop($data['bpi']);

        yield [
            'base' => CoinDeskSourceData::BASE,
            'quote' => CoinDeskSourceData::QUOTE,
            'price' => (string)$price,
            'provider' => CoinDeskSourceData::PROVIDER_NAME,
        ];
    }

    private function fetch(): array
    {
        $response = $this->client->request('GET', static::URL);
        if (200 === $response->getStatusCode()) {
            return $response->toArray(false);
        }
        throw new RuntimeException('unable to fetch coindesk rates');
    }
}

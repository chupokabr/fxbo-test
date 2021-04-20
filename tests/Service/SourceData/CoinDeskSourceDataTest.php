<?php

namespace App\Tests\Service\SourceData;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use App\Service\SourceData\CoinDeskSourceData;

class CoinDeskSourceDataTest extends TestCase
{
    public function testProcess(): void
    {
        $client = new MockHttpClient([
            new MockResponse('{"bpi":{"2021-04-10":50123.265, "2021-04-11":52453.346, "2021-04-15":65987.777}}',
                ['http_code' => 200]),
        ], 'http://localhost');

        $sourceData = new CoinDeskSourceData($client);

        self::assertEquals([
            [
                'base' => 'BTC',
                'quote' => 'USD',
                'price' => '65987.777',
                'provider' => 'coindesk',
            ],
        ], iterator_to_array($sourceData->process()));

    }
}

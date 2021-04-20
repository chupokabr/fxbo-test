<?php

namespace App\Tests\Service\SourceData;

use App\Service\SourceData\EcbSourceData;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class EcbSourceDataTest extends TestCase
{
    private function prepareResponse()
    {
        return <<<XML
<gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref" capture-installed="true">
<gesmes:subject>Reference rates</gesmes:subject>
<gesmes:Sender>
<gesmes:name>European Central Bank</gesmes:name>
</gesmes:Sender>
<Cube>
<Cube time="2021-04-15">
<Cube currency="USD" rate="1.1985"/>
<Cube currency="RUB" rate="92.2336"/>
</Cube>
</Cube>
</gesmes:Envelope>
XML;
    }

    public function testProcess(): void
    {
        $xml = $this->prepareResponse();

        $client = new MockHttpClient([
            new MockResponse($xml, ['http_code' => 200]),
        ], 'http://localhost');

        $sourceData = new EcbSourceData($client);

        self::assertEquals([
            [
                'base' => 'EUR',
                'quote' => 'USD',
                'price' => '1.1985',
                'provider' => 'ecb',
            ],
            [

                'base' => 'EUR',
                'quote' => 'RUB',
                'price' => '92.2336',
                'provider' => 'ecb',
            ],
        ], iterator_to_array($sourceData->process()));

    }
}

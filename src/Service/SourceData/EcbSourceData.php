<?php

declare(strict_types=1);

namespace App\Service\SourceData;

use RuntimeException;
use SimpleXMLElement;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Traversable;

final class EcbSourceData implements SourceDataInterface
{
    private const BASE = 'EUR';
    private const URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    private const PROVIDER_NAME = 'ecb';

    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $ecb
    )
    {
        $this->client = $ecb;
    }

    public function process(): Traversable
    {
        $data = $this->fetch();
        $data->registerXPathNamespace('xmlns', 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref');
        /** @var SimpleXMLElement|false $elements */
        $elements = $data->xpath('//xmlns:Cube[@currency]');
        if (false === $elements) {
            throw new RuntimeException('unable to fetch ecb rates');
        }
        /** @var SimpleXMLElement $element */
        foreach ($elements as $i => $element) {
            yield [
                'base' => EcbSourceData::BASE,
                'quote' => (string)$element->xpath('//@currency')[$i],
                'price' => (string)$element->xpath('//@rate')[$i],
                'provider' => EcbSourceData::PROVIDER_NAME,
            ];
        }
    }

    private function fetch(): SimpleXMLElement
    {
        $response = $this->client->request('GET', EcbSourceData::URL);
        if (200 === $response->getStatusCode()) {
            return new SimpleXMLElement($response->getContent());
        }
        throw new RuntimeException('unable to fetch ecb rates');
    }
}

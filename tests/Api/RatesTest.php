<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Rate;

class RatesTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/rates');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Rate',
            '@id' => '/api/rates',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 5,
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(5, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Rate::class);
    }

    public function testGetRate(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Rate::class, ['base' => 'ABC', 'quote' => 'GGG']);

        $client->request('GET', $iri);

        $this->assertResponseIsSuccessful();

        $this->assertMatchesResourceItemJsonSchema(Rate::class);

        $this->assertJsonContains([
            '@id' => $iri,
            'base' => 'ABC',
            'quote' => 'GGG',
        ]);

    }

    public function testUpdateRate(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Rate::class, ['base' => 'ABC', 'quote' => 'GGG']);

        $client->request('PUT', $iri, ['json' => [
            'price' => '100500',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'base' => 'ABC',
            'quote' => 'GGG',
            'price' => '100500.00000000',
        ]);
    }


    public function testDeleteRate(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Rate::class, ['base' => 'ABC', 'quote' => 'GGG']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
        // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::$container->get('doctrine')->getRepository(Rate::class)->findOneBy(['base' => 'ABC', 'quote' => 'GGG'])
        );
    }

}
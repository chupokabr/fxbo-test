<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Rate;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $testRateFixture = [
            [
                'base' => 'ABC',
                'quote' => 'GGG',
                'price' => '1',
                'provider' => 'test'
            ],
            [
                'base' => 'YYY',
                'quote' => 'KKK',
                'price' => '5',
                'provider' => 'test'
            ],
            [
                'base' => 'FFF',
                'quote' => 'KKK',
                'price' => '0.5',
                'provider' => 'test'
            ],
            [
                'base' => 'GGG',
                'quote' => 'FFF',
                'price' => '0.1',
                'provider' => 'test'
            ],
            [
                'base' => 'ZZZ',
                'quote' => 'ABC',
                'price' => '2',
                'provider' => 'test'
            ]
        ];

        foreach ($testRateFixture as $testRate) {
            $rate = new Rate();
            $rate->setBase($testRate['base']);
            $rate->setQuote($testRate['quote']);
            $rate->setPrice($testRate['price']);
            $rate->setProvider($testRate['provider']);
            $manager->persist($rate);
        }

        $manager->flush();
    }
}

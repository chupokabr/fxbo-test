<?php

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    /**
     * @param array $rates
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function batchUpsert(array $rates)
    {
        $entityManager = $this->getEntityManager();

        foreach ($rates as $rate) {
            $rateEntity = $this->prepareRate($rate['base'], $rate['quote'], $rate['provider'], $rate['price']);
            $entityManager->persist($rateEntity);
        }

        $entityManager->flush();
    }


    /**
     * @param string $base
     * @param string $quote
     * @param string $provider
     * @param string $price
     * @return Rate|null
     */
    private function prepareRate(string $base, string $quote, string $provider, string $price)
    {
        $rateEntity = $this->findRateInDB(
            $base,
            $quote,
            $provider
        );

        if (is_null($rateEntity)) {
            $rateEntity = new Rate();
        }

        $rateEntity
            ->setBase($base)
            ->setQuote($quote)
            ->setPrice($price)
            ->setProvider($provider);

        return $rateEntity;
    }


    /**
     * @param $base
     * @param $quote
     * @param $provider
     * @return Rate|null
     */
    private function findRateInDB($base, $quote, $provider)
    {
        return $this->findOneBy([
            'base' => $base,
            'quote' => $quote,
            'provider' => $provider
        ]);
    }
}

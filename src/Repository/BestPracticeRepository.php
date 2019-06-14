<?php

namespace App\Repository;

use App\Entity\BestPractice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BestPractice|null find($id, $lockMode = null, $lockVersion = null)
 * @method BestPractice|null findOneBy(array $criteria, array $orderBy = null)
 * @method BestPractice[]    findAll()
 * @method BestPractice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BestPracticeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BestPractice::class);
    }
}

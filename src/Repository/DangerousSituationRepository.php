<?php

namespace App\Repository;

use App\Entity\DangerousSituation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DangerousSituation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DangerousSituation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DangerousSituation[]    findAll()
 * @method DangerousSituation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DangerousSituationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DangerousSituation::class);
    }
}

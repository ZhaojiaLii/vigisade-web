<?php

namespace App\Repository;

use App\Entity\TypeDangerousSituation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeDangerousSituation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDangerousSituation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDangerousSituation[]    findAll()
 * @method TypeDangerousSituation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDangerousSituationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeDangerousSituation::class);
    }
}

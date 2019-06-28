<?php

namespace App\Repository;

use App\Entity\CorrectiveAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CorrectiveAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorrectiveAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorrectiveAction[]    findAll()
 * @method CorrectiveAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrectiveActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CorrectiveAction::class);
    }
}

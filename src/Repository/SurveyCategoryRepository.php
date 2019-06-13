<?php

namespace App\Repository;

use App\Entity\SurveyCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SurveyCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyCategory[]    findAll()
 * @method SurveyCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SurveyCategory::class);
    }
}

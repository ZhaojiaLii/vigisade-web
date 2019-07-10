<?php

namespace App\Repository;

use App\Entity\SurveyQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyQuestionRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, SurveyQuestion::class);
        $this->em = $em;
    }

    public function getSurveyQuestionByID($id)
    {
        $surveyQuestion = $this->em
            ->getRepository(SurveyQuestion::class)
            ->find($id);

        if (!$surveyQuestion) {
            throw new NotFoundException("This Question not exist ".$id);
        }
        return $surveyQuestion;
    }
}

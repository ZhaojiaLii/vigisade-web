<?php

namespace App\Repository;

use App\Entity\SurveyCategory;
use App\Entity\SurveyCategoryTranslation;
use App\Repository\SurveyCategoryTranslationRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SurveyCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyCategory[]    findAll()
 * @method SurveyCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyCategoryRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SurveyCategoryRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     * @param \App\Repository\SurveyCategoryTranslationRepository $surveyCategoryTranslationRepository
     * @param SurveyQuestionRepository $surveyQuestionRepository
     */
    public function __construct(RegistryInterface $registry,
                                EntityManagerInterface $em,
                                SurveyCategoryTranslationRepository $surveyCategoryTranslationRepository,
                                SurveyQuestionRepository $surveyQuestionRepository)
    {
        parent::__construct($registry, SurveyCategory::class);
        $this->em = $em;
        $this->surveyCategoryTranslationRepository = $surveyCategoryTranslationRepository;
        $this->surveyQuestionRepository = $surveyQuestionRepository;
    }

    /**
     * @param $idSurvey
     * @return array
     */
    public function getSurveyCategory($idSurvey, $userLanguage)
    {
        $surveyCategories = $this->em
            ->getRepository(SurveyCategory::class)->findBy(['survey' => $idSurvey]);

        $responseArray = [];
        foreach($surveyCategories as $surveyCategory){
            $responseArray[] = [
                "surveyCategoryId" => $surveyCategory->getId(),
                "surveyCategoryOrdonnancement" => $surveyCategory->getCategoryOrder(),
                "surveyCategoryTitleTranslation" =>
                    $this->surveyCategoryTranslationRepository->getSurveyCategoriesTranslation($surveyCategory->getId(), $userLanguage),
                "surveyQuestion" => $this->surveyQuestionRepository->getSurveyQuestion($surveyCategory->getId(), $userLanguage),
            ];
        }
        return $responseArray;
    }
}

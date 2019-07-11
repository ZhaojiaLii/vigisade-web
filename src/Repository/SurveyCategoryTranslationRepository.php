<?php

namespace App\Repository;

use App\Entity\SurveyCategoryTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SurveyCategoryTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyCategoryTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyCategoryTranslation[]    findAll()
 * @method SurveyCategoryTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyCategoryTranslationRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, SurveyCategoryTranslation::class);
        $this->em = $em;
    }

    /**
     * @param $idBestPratique
     * @return array
     */
    public function getSurveyCategoriesTranslation($idSurveyCategory){

        $surveyCategoriestranslation = $this->em
            ->getRepository(SurveyCategoryTranslation::class)->findBy(['translatable' => $idSurveyCategory]);

        $responseArray = [];
        foreach($surveyCategoriestranslation as $surveyCategorytranslation){
            $responseArray[] = [
                "survey_category_translation_id" => $surveyCategorytranslation->getId(),
                "survey_category_translatable_id" => $surveyCategorytranslation->getTranslatable()->getId(),
                "survey_category_translatable_title" => $surveyCategorytranslation->getTitle(),
                "survey_category_translatable_locale" => $surveyCategorytranslation->getLocale(),
            ];
        }

        return $responseArray;
    }
}

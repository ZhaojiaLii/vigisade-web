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
                "surveyCategoryTranslationId" => $surveyCategorytranslation->getId(),
                "surveyCategoryTranslatableId" => $surveyCategorytranslation->getTranslatable()->getId(),
                "surveyCategoryTranslatableTitle" => $surveyCategorytranslation->getTitle(),
                "surveyCategoryTranslatableLocale" => $surveyCategorytranslation->getLocale(),
            ];
        }

        return $responseArray;
    }
}

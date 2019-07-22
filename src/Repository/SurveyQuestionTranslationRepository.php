<?php

namespace App\Repository;

use App\Entity\SurveyQuestionTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SurveyQuestionTranslationRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SurveyRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, SurveyQuestionTranslation::class);
        $this->em = $em;
    }

    /**
     * @param $idBestPratique
     * @return array
     */
    public function getSurveyQuestionTranslation($idSurveyQuestion, $userLanguage){

        $surveyQuestiontranslation = $this->em
            ->getRepository(SurveyQuestionTranslation::class)->findBy(['translatable' => $idSurveyQuestion]);

        $responseArray = [];
        foreach($surveyQuestiontranslation as $questiontranslation){
            if($questiontranslation->getLocale() === $userLanguage) {
                $responseArray[] = [
                    "surveyQuestionTranslationId" => $questiontranslation->getId(),
                    "surveyQuestionTranslationTranslatableId" => $questiontranslation->getTranslatable()->getId(),
                    "surveyQuestionTranslationLabel" => $questiontranslation->getLabel(),
                    "surveyQuestionTranslationHelp" => $questiontranslation->getHelp(),
                    "surveyQuestionTranslationLocale" => $questiontranslation->getLocale(),
                ];
            }
        }
        return $responseArray;
    }
}

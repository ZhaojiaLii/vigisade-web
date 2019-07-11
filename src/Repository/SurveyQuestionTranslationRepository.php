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
    public function getSurveyQuestionTranslation($idSurveyQuestion){

        $surveyQuestiontranslation = $this->em
            ->getRepository(SurveyQuestionTranslation::class)->findBy(['translatable' => $idSurveyQuestion]);

        $responseArray = [];
        foreach($surveyQuestiontranslation as $questiontranslation){
            $responseArray[] = [
                "survey_question_translation_id" => $questiontranslation->getId(),
                "survey_question_translation_translatable_id" => $questiontranslation->getTranslatable()->getId(),
                "survey_question_translation_label" => $questiontranslation->getLabel(),
                "survey_question_translation_help" => $questiontranslation->getHelp(),
                "survey_question_translation_locale" => $questiontranslation->getLocale(),
            ];
        }
        return $responseArray;
    }
}

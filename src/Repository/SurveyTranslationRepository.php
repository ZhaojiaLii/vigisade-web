<?php

namespace App\Repository;

use App\Entity\Survey;
use App\Entity\SurveyTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SurveyTranslationRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SurveyTranslationRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, SurveyTranslation::class);
        $this->em = $em;
    }

    /**
     * @param $idSurvey
     * @return array
     */
    public function getBestPracticeTranslation($idSurvey){

        $surveytranslation = $this->em
            ->getRepository(SurveyTranslation::class)->findBy(['translatable' => $idSurvey]);

        $responseArray = [];
        foreach($surveytranslation as $survey){
            $responseArray[] = [
                "survey_translation_id" => $survey->getId(),
                "survey_translation_best_practice_label" => $survey->getbestPracticeLabel(),
                "survey_translation_best_practice_help" => $survey->getbestPracticeHelp(),
                "survey_translation_locale" => $survey->getLocale(),
            ];
        }

        return $responseArray;
    }
}

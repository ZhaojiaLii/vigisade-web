<?php

namespace App\Repository;

use App\Entity\Survey;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SurveyRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Survey::class);
        $this->em = $em;
    }

    /**
     * @return array
     * get best practice by local
     */
    public function getBestPractice()
    {
        $surveys = $this->em
            ->getRepository(Survey::class)->findAll();

        if (!$surveys) {
            throw new NotFoundException("This Survey empty in DB");
        }

        $responseArray = [];
        foreach($surveys as $survey){

            $responseArray[] = [
                "survey_translation_id" => $survey->getId(),
                "survey_translation_best_practice_label" => $survey->getTranslatable()->getbestPracticeLabel(),
                "survey_translation_best_practice_help" => $survey->getTranslatable()->getbestPracticeHelp(),
                "survey_translation_locale" => $survey->getTranslatable()->getLocale(),
            ];
        }

        return $responseArray;
    }
}

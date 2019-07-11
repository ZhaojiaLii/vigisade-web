<?php

namespace App\Repository;

use App\Entity\SurveyQuestion;
use App\Repository\SurveyQuestionTranslationRepository;
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

    public function __construct(RegistryInterface $registry,
                                EntityManagerInterface $em,
                                SurveyQuestionTranslationRepository $surveyQuestionTranslationRepository)
    {
        parent::__construct($registry, SurveyQuestion::class);
        $this->em = $em;
        $this->surveyQuestionTranslationRepository = $surveyQuestionTranslationRepository;
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

    public function getSurveyQuestion($idSurveyCategory){

        $surveyquestions = $this->em
            ->getRepository(SurveyQuestion::class)->findBy(['category' => $idSurveyCategory]);
        $responseArray = [];
        foreach($surveyquestions as $surveyquestion){
            $responseArray[] = [
                "survey_question_id" => $surveyquestion->getId(),
                "survey_question_Ordonnancement" => $surveyquestion->getQuestionOrder(),
                "survey_question_type" => $surveyquestion->getQuestionType(),
                "survey_question_category_id" => $idSurveyCategory,
                "survey_question_translation" => $this->surveyQuestionTranslationRepository->getSurveyQuestionTranslation($surveyquestion->getId()),
            ];
        }
        return $responseArray;
    }
}

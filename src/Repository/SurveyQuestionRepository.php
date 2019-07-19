<?php

namespace App\Repository;

use App\Entity\SurveyQuestion;
use App\Exception\Http\NotFoundException;
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

    public function getSurveyQuestion($idSurveyCategory, $userLanguage){

        $surveyquestions = $this->em
            ->getRepository(SurveyQuestion::class)->findBy(['category' => $idSurveyCategory]);
        $responseArray = [];
        foreach($surveyquestions as $surveyquestion){
            $responseArray[] = [
                "surveyQuestionId" => $surveyquestion->getId(),
                "surveyQuestionOrdonnancement" => $surveyquestion->getQuestionOrder(),
                "surveyQuestionType" => $surveyquestion->getQuestionType(),
                "surveyQuestionCategoryId" => $idSurveyCategory,
                "surveyQuestionTranslation" =>
                    $this->surveyQuestionTranslationRepository->getSurveyQuestionTranslation($surveyquestion->getId(), $userLanguage),
            ];
        }
        return $responseArray;
    }
}

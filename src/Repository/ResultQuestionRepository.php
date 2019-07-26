<?php

namespace App\Repository;

use App\Entity\ResultQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResultQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultQuestion[]    findAll()
 * @method ResultQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultQuestionRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, ResultQuestion::class);
        $this->em = $em;
    }

    public function getQuestionByResult($idResult)
    {
        $questions = $this->em
            ->getRepository(ResultQuestion::class)
            ->findBy(['result' => $idResult]);

        $responseArray = [];
        foreach ($questions as $question) {
            $responseArray[] = [
                "resultQuestionId" => $question->getId(),
                "resultQuestionResultId" => $question->getResult()->getId(),
                "resultQuestionResultQuestionId" => $question->getQuestion()->getId(),
                "resultQuestionTeamMemberId" => $question->getTeamMembers()->getId(),
                "resultQuestionResultNotation" => $question->getNotation(),
                "resultQuestionResultComment" => $question->getComment(),
                "resultQuestionResultPhoto" => $question->getPhoto(),
            ];
        }

        return $responseArray;
    }
}

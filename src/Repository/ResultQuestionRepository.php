<?php

namespace App\Repository;

use App\Entity\ResultQuestion;
use App\Exception\Http\NotFoundException;
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

        if (!$questions) {
            throw new NotFoundException("This Result id =.$idResult.' dont have a question ");
        }

        foreach ($questions as $question) {
            $responseArray[] = [
                "resultQuestionId" => $question->getId(),
                "resultQuestionResultId" => $question->getResult()->getId(),
                "resultQuestionResultQuestionId" => $question->getQuestion(), //null
                "resultQuestionTeamMemberId" => "##### Member ID######",
                "resultQuestionResultNotation" => $question->getNotation(),
                "resultQuestionResultComment" => $question->getComment(),
                "resultQuestionResultPhoto" => $question->getPhoto(),
            ];
        }

        return $responseArray;

    }
}

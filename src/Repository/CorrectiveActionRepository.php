<?php

namespace App\Repository;

use App\Entity\CorrectiveAction;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method CorrectiveAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorrectiveAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorrectiveAction[]    findAll()
 * @method CorrectiveAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrectiveActionRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, CorrectiveAction::class);
        $this->em = $em;
    }

    public function getCorrectiveActionByID($id)
    {
        $correctiveAction = $this->em
            ->getRepository(CorrectiveAction::class)
            ->find($id);

        if (!$correctiveAction) {
            $message = ['message' => "This Corrective Action with id ".$id." not exist "];

            return new JsonResponse($message, 200);
        }
        return $correctiveAction;
    }
}

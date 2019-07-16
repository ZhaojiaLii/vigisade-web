<?php

namespace App\Repository;

use App\Entity\DangerousSituation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DangerousSituation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DangerousSituation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DangerousSituation[]    findAll()
 * @method DangerousSituation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DangerousSituationRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, DangerousSituation::class);
        $this->em = $em;
    }

    public function getDangerousSituationByID($id)
    {
        $situationDangerous = $this->find($id);

        if (!$situationDangerous) {
            throw new NotFoundException("This Dangerous Situation with id ".$id." not exist ");
        }

        $response = [
                "userId" => $situationDangerous->getUser()->getId(),
                "dangerousSituationId" => $situationDangerous->getId(),
                "dangerousSituationTypeId" => $situationDangerous->getTypeDangerousSituation()->getId(),
                "dangerousSituationDirectionId" => $situationDangerous->getDirection()->getId(),
                "dangerousSituationAreaId" => $situationDangerous->getArea()->getId(),
                "dangerousSituationEntityId" => $situationDangerous->getEntity()->getId(),
                "dangerousSituationDate" => $situationDangerous->getDate(),
                "dangerousSituationComment" => $situationDangerous->getComment(),
                "dangerousSituationPhoto" => $situationDangerous->getPhoto(),
            ];

        return $response;

    }
}

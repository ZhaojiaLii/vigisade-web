<?php

namespace App\Repository;

use App\Entity\ResultTeamMember;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResultTeamMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultTeamMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultTeamMember[]    findAll()
 * @method ResultTeamMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultTeamMemberRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, ResultTeamMember::class);
        $this->em = $em;
    }

    public function getResultTeamMemberByResult($idResult)
    {
        $teamMembers = $this->em
            ->getRepository(ResultTeamMember::class)
            ->findBy(['result' => $idResult]);

        if (!$teamMembers) {
            throw new NotFoundException("This Result with id ".$idResult." dont have a Team Member ");
        }

        foreach ($teamMembers as $teamMember) {
            $responseArray[] = [
                "resultTeamMemberId" => $teamMember->getId(),
                "resultTeamMemberFirstName" => $teamMember->getFirstName(),
                "resultTeamMemberLastName" => $teamMember->getLastName(),
                "resultTeamMemberRole" => $teamMember->getRole()
            ];
        }

        return $responseArray;

    }
}

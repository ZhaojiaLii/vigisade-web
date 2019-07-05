<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Direction;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Direction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Direction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Direction[]    findAll()
 * @method Direction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectionRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Direction::class);
        $this->em = $em;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getDirectionByName($name)
    {
        $direction = $this->em
            ->getRepository(Direction::class)
            ->findBy(['name' => $name]);

        if (!$direction) {
            throw new NotFoundException("This direction not exist ".$name);
        }
        return $direction[0];
    }

    //todo remove all Direction
}

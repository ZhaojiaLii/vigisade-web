<?php

namespace App\Repository;

use App\Entity\Area;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Area::class);
        $this->em = $em;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getAreaByName($name)
    {
        $area = $this->em
            ->getRepository(Area::class)
            ->findBy(['name' => $name]);

        if (!$area) {
            return false;
        }
        return $area[0];
    }

    //todo remove all Area
}

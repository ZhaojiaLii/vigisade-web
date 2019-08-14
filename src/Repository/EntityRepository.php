<?php

namespace App\Repository;

use App\Entity\Entity;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Entity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findAll()
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Entity::class);
        $this->em = $em;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getEntityByName($name)
    {
        $entity = $this->em
            ->getRepository(Entity::class)
            ->findBy(['name' => $name]);

        if (!$entity) {
            return false;
        }
        return $entity[0];
    }

    //todo remove all entity
}

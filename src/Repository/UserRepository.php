<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, User::class);
        $this->em = $em;
    }

    /**
     * @param int $userId
     * @return integer
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountLastMonthVisits(int $userId)
    {
        $dateStart = new \DateTime('first day of previous month 00:00:00');
        $dateEnd = new \DateTime('last day of previous month 23:59:59');

        $query = $this->em->createQuery('
            SELECT COUNT(r.user)
            FROM App\Entity\Result r
            WHERE r.user = :user_id
            AND r.date >= :date_start
            AND r.date <= :date_end            
            '
        )->setParameter('user_id', $userId)
        ->setParameter('date_start', $dateStart)
        ->setParameter('date_end', $dateEnd);

        $result = $query->getSingleResult();

        return (int) $result[1];
    }

    /**
     * @param int $userId
     * @return integer
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountCurrentMonthVisits(int $userId)
    {
        $dateStart = new \DateTime('first day of this month 00:00:00');
        $dateEnd = new \DateTime('last day of this month 23:59:59');

        $query = $this->em->createQuery('
            SELECT COUNT(r.user)
            FROM App\Entity\Result r
            WHERE r.user = :user_id
            AND r.date >= :date_start
            AND r.date <= :date_end            
            '
        )->setParameter('user_id', $userId)
            ->setParameter('date_start', $dateStart)
            ->setParameter('date_end', $dateEnd);

        $result = $query->getSingleResult();

        return (int) $result[1];
    }

    /**
     * @param int $userId
     * @return integer
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountRemainingActions(int $userId)
    {
        $query = $this->em->createQuery('
            SELECT COUNT(c.user)
            FROM App\Entity\CorrectiveAction c
            WHERE c.user = :user_id
            AND c.status = \'à traiter\'       
            '
        )->setParameter('user_id', $userId);

        $result = $query->getSingleResult();

        return (int) $result[1];
    }

    public function RemoveUsers()
    {
        $users = $this->em->getRepository(User::class)->findAll();
        foreach ($users as $user){
            $this->em->remove($user);
            $this->em->flush();
        }
    }
}

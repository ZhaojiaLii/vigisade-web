<?php

namespace App\Repository;

use App\Entity\BestPractice;
use App\Entity\BestPracticeTranslation;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BestPractice|null find($id, $lockMode = null, $lockVersion = null)
 * @method BestPractice|null findOneBy(array $criteria, array $orderBy = null)
 * @method BestPractice[]    findAll()
 * @method BestPractice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BestPracticeRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BestPracticeRepository constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, BestPractice::class);
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAllTypeBestPractice()
    {
        $typeBestPratique = $this->em
            ->getRepository(BestPractice::class)->findAll();

        if (!$typeBestPratique) {
            throw new NotFoundException("This type Best practice empty in DB");
        }

        $responseArray = [];
        foreach($typeBestPratique as $tbestpratique){
            $responseArray[] = [
                "typeBestPracticeTranslationId" => $tbestpratique->getId(),
                "typeBestPracticeTranslationType" => $tbestpratique->getType(),
                "typeBestPracticeTranslationLocale" => $tbestpratique->getLocale()
            ];
        }

        return $responseArray;
    }
}

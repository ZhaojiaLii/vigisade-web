<?php

namespace App\Repository;

use App\Entity\BestPractice;
use App\Entity\BestPracticeTranslation;
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
                "type_best_practice_id" => $tbestpratique->getId(),
                "type_best_practice_translation" => $this->getTypeBestPracticeTranslation($tbestpratique->getId()),
            ];
        }

        return $responseArray;
    }

    /**
     * @param $idBestPractice
     * @return array
     */
    public function getTypeBestPracticeTranslation($idBestPractice){

        $typeBestPracticeTranslation = $this->em
            ->getRepository(BestPracticeTranslation::class)->findBy(['translatable' => $idBestPractice]);

        $responseArray = [];
        foreach($typeBestPracticeTranslation as $tbestpracticeTranslation){
            $responseArray[] = [
                "type_best_practice_translation_id" => $tbestpracticeTranslation->getId(),
                "type_best_practice_translation_translatable_id" => $tbestpracticeTranslation->getTranslatable()->getId(),
                "type_best_practice_translation_type" => $tbestpracticeTranslation->getType(),
                "type_best_practice_translation_locale" => $tbestpracticeTranslation->getLocale(),
            ];
        }

        return $responseArray;
    }
}

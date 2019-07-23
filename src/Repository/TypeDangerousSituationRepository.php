<?php

namespace App\Repository;

use App\Entity\TypeDangerousSituation;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeDangerousSituation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDangerousSituation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDangerousSituation[]    findAll()
 * @method TypeDangerousSituation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDangerousSituationRepository extends ServiceEntityRepository
{
    private $typeDangerousSituationTranslationRepository;

    public function __construct(
        RegistryInterface $registry,
        TypeDangerousSituationTranslationRepository $typeDangerousSituationTranslationRepository )
    {
        parent::__construct($registry, TypeDangerousSituation::class);
        $this->typeDangerousSituationTranslationRepository = $typeDangerousSituationTranslationRepository;
    }

    public function getAllTypeDangerousSituation($userLanguage)
    {
        $typeDangerousSituations = $this->findAll();

        if($typeDangerousSituations){

            $responseArray = [];
            foreach ($typeDangerousSituations as $typeDangerousSituation ){

                $responseArray []  = [
                    "typeDangerousSituationsId" => $typeDangerousSituation->getId(),
                    "typeDangerousSituationTranslation" =>
                        $this->typeDangerousSituationTranslationRepository->getAllTypeDangerousSituationTranslation($typeDangerousSituation->getId(), $userLanguage)
                ];
            }

            return $responseArray;
        }
    }
}

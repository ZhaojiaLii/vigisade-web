<?php

namespace App\Repository;

use App\Entity\TypeDangerousSituationTranslation;
use App\Exception\Http\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeDangerousSituationTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDangerousSituationTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDangerousSituationTranslation[]    findAll()
 * @method TypeDangerousSituationTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDangerousSituationTranslationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeDangerousSituationTranslation::class);
    }

    public function getAllTypeDangerousSituationTranslation($id, $userLanguage)
    {
        $typeDangerousSituationsTranslation = $this->findBy(['translatable' => $id]);

        if($typeDangerousSituationsTranslation){
            foreach ($typeDangerousSituationsTranslation as $typeDangerousSituationTranslation ){
                if($typeDangerousSituationTranslation->getLocale() === $userLanguage){
                    $responseArray []  = [
                        "typeDangerousSituationTranslationId" => $typeDangerousSituationTranslation->getId(),
                        "typeDangerousSituationTranslationTranslatableId" => $typeDangerousSituationTranslation->getTranslatable()->getId(),
                        "typeDangerousSituationTranslationType" => $typeDangerousSituationTranslation->getType(),
                        "typeDangerousSituationTranslationLocale" => $typeDangerousSituationTranslation->getLocale()
                    ];
                }

            }

            return $responseArray;
        }
    }
}

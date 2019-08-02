<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Repository\DirectionRepository;
use App\Repository\TypeDangerousSituationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DirectionZoneEntityController extends ApiController
{
    private $directionRepository;
    private $typeDangerousSituationRepository;

    public function __construct(
        DirectionRepository $directionRepository,
        TypeDangerousSituationRepository $typeDangerousSituationRepository)
    {
        $this->directionRepository = $directionRepository;
        $this->typeDangerousSituationRepository = $typeDangerousSituationRepository;
    }

    /**
     * Gets Information about Directions, Areas and entities
     * @return \FOS\RestBundle\View\View
     */
    public function getDirectionZoneEntityTypeDangerousSituation() {

        $userLanguage = $this->getUser()->getLanguage();

        // list Direction / Area / Entities / type Dangerous situations
        $list = [];
        $directions = $this->directionRepository->findAll();

        if ($directions) {

            // Directions
            foreach ($directions as $direction) {
                $directionData = [];
                $directionData['id'] = $direction->getId();
                $directionData['name'] = $direction->getName();

                //Areas
                $areasList = [];
                if ($direction->getAreas()) {
                    foreach ($direction->getAreas() as $area) {
                        $areaData = [];
                        $areaData['id'] = $area->getId();
                        $areaData['direction'] = $area->getDirection()->getId();
                        $areaData['name'] = $area->getName();

                        //Entities
                        $entitiesList = [];
                        $entityData = [];
                        if ($area->getEntities()) {
                            foreach ($area->getEntities() as $entity) {
                                $entityData['id'] = $entity->getId();
                                $entityData['area_id'] = $entity->getArea()->getId();
                                $entityData['name'] = $entity->getName();
                                $entitiesList['entity'][] = $entityData;
                            }
                        }
                        $areaData = array_merge($areaData, $entitiesList);
                        $areasList[] = $areaData;
                    }
                }
                $directionData['area'] = $areasList;
                $list['direction'][] = $directionData;
            }
        }

        //Get type Dangerous situations
        $list['typeDangerousSituations'] = $this->typeDangerousSituationRepository->getAllTypeDangerousSituation($userLanguage);

        return $this->createResponse('DIRECTION_ZONE_ENTITY_TYPE_DANGEROUSE_SITUATION', $list);

    }
}

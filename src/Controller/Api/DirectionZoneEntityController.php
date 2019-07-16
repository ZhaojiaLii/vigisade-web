<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Repository\DirectionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DirectionZoneEntityController extends ApiController
{
    private $directionRepository;

    public function __construct(DirectionRepository $directionRepository)
    {
        $this->directionRepository = $directionRepository;
    }

    /**
     * Gets Information about Directions, Areas and entities 
     * @return \FOS\RestBundle\View\View
     */
    public function getDirectionZoneEntity() {

        $directionsList = [];
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
                        if ($area->getEntities()) {
                            foreach ($area->getEntities() as $entity) {
                                $entityData = [];
                                $entityData['id'] = $entity->getId();
                                $entityData['area_id'] = $entity->getArea()->getId();
                                $entityData['name'] = $entity->getName();
                                $entitiesList['entity'][] = $entityData;
                            }
                        }
                        $areaData['entity'] = $entitiesList;
                        $areasList[] = $areaData;
                    }
                }
                $directionData['area'] = $areasList;
                $directionsList['direction'][] = $directionData;
            }
        }

        return $this->createResponse('DIRECTION_ZONE_ENTITY', $directionsList);

    }
}

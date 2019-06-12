<?php

namespace App\Controller\Back;

use App\Entity\Area;
use App\Entity\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AsyncController extends AbstractController
{
    public function getSpecificAreaSelect(EntityManagerInterface $em, Request $request)
    {
        $idDirection = $request->request->get('id_direction');
        $areas = $em->getRepository(Area::class)->findBy(['direction' => $idDirection]);

        $responseArray = [];
        foreach($areas as $area){
            $responseArray[] = [
                "id" => $area->getId(),
                "name" => $area->getName()
            ];
        }

        return new JsonResponse($responseArray);
    }

    public function getSpecificEntitySelect(EntityManagerInterface $em, Request $request)
    {
        $idArea = $request->request->get('id_area');
        $entities = $em->getRepository(Entity::class)->findBy(['area' => $idArea]);

        $responseArray = [];
        foreach($entities as $entity){
            $responseArray[] = [
                "id" => $entity->getId(),
                "name" => $entity->getName()
            ];
        }

        return new JsonResponse($responseArray);
    }
}

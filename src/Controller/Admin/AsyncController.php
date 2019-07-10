<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Entity;
use App\Entity\Survey;
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

    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return JsonResponse
     */
    public function getDirectionSurveySelect(EntityManagerInterface $em, Request $request)
    {
        $idDirection = $request->request->get('id_direction');
        $surveys = $em->getRepository(Survey::class)->findBy(['direction' => $idDirection]);
        if(!$surveys){
            return new JsonResponse(['message' => '']);
        }

        return new JsonResponse(['message' =>
            'this direction is used by Survey : '.$surveys[0]->getTitle().' with ID : '.$surveys[0]->getID()]);
    }
}

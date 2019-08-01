<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Direction;
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

            return new JsonResponse(['message' => 'null']);
        }

        return new JsonResponse(['message' =>
            'Cette direction est utilisé par le formulaire : '.$surveys[0]->getTitle().' qui possède ID : '.$surveys[0]->getID()]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return JsonResponse
     */
    public function getDirectionUniqueName(EntityManagerInterface $em, Request $request)
    {
        $direction = $em->getRepository(Direction::class)->findBy([
            'name' => $request->request->get('direction_name')
        ]);

        if($direction){

            return new JsonResponse(['message' => 'Ce nom est déjà utilisé par Direction qui possède ID = '.$direction[0]->getId()]);
        }

        return new JsonResponse(['message' => 'null']);
    }

    public function getAreaUniqueName(EntityManagerInterface $em, Request $request)
    {
        $area = $em->getRepository(Area::class)->findBy([
            'name' => $request->request->get('area_name')
        ]);

        if($area){

            return new JsonResponse(['message' => 'Ce nom est déjà utilisé par Zone qui possède ID = '.$area[0]->getId()]);
        }

        return new JsonResponse(['message' => 'null']);
    }

    public function getEntityUniqueName(EntityManagerInterface $em, Request $request)
    {
        $entity = $em->getRepository(Entity::class)->findBy([
            'name' => $request->request->get('entity_name')
        ]);

        if($entity){

            return new JsonResponse(['message' => 'Ce nom est déjà utilisé par Entité qui possède ID = '.$entity[0]->getId()]);
        }

        return new JsonResponse(['message' => 'null']);
    }
}

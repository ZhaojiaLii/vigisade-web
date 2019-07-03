<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\CorrectiveAction;
use App\Exception\Http\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorrectiveActionController extends ApiController
{
    private $serializer;
    private $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function getCorrectiveActions()
    {
        $CorrectiveAction = $this->em
            ->getRepository(CorrectiveAction::class)
            ->findBy(['user' => $this->getUser(), 'status' => 'A traiter']);

        if (!$CorrectiveAction) {
            throw new NotFoundException("This user dont have Corrective Action");
        }

        return $this->createResponse('CorrectiveAction', $CorrectiveAction);
    }

    public function createResult(Request $request)
    {
        $data = $request->getContent();
        $correctiveAction = $this->serializer->deserialize($data, 'App\Entity\CorrectiveAction', 'json');
        $correctiveAction->setUser($this->getUser());

        $this->em->persist($correctiveAction);
        $this->em->flush();

        $message = ['message' => 'the corrective action `'.$correctiveAction->getId().'` has been saved .'];
        return new JsonResponse($message, Response::HTTP_CREATED);
    }

    public function updateResult(Request $request)
    {
        $data = $request->getContent();
        $correctiveAction = $this->serializer->deserialize($data, 'App\Entity\CorrectiveAction', 'json');
        $this->em->persist($correctiveAction);
        $this->em->flush();

        $message = ['message' => 'the corrective action `'.$correctiveAction->getId().'` has been updated .'];
        return new JsonResponse($message, Response::HTTP_CREATED);
    }
}

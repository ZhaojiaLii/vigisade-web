<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\CorrectiveAction;
use App\Exception\Http\NotFoundException;
use App\Repository\CorrectiveActionRepository;
use App\Repository\ResultRepository;
use App\Repository\SurveyQuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CorrectiveActionController extends ApiController
{
    private $serializer;
    private $em;
    private $surveyQuestionRepository;
    private $correctiveActionRepository;
    private $resultRepository;

    /**
     * CorrectiveActionController constructor.
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param SurveyQuestionRepository $surveyQuestionRepository
     * @param ResultRepository $resultRepository
     * @param CorrectiveActionRepository $correctiveActionRepository
     */
    public function __construct(SerializerInterface $serializer,
                                EntityManagerInterface $em,
                                SurveyQuestionRepository $surveyQuestionRepository,
                                ResultRepository $resultRepository,
                                CorrectiveActionRepository $correctiveActionRepository)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->surveyQuestionRepository = $surveyQuestionRepository;
        $this->resultRepository = $resultRepository;
        $this->correctiveActionRepository = $correctiveActionRepository;
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function getCorrectiveActions()
    {
        $CorrectivesAction = $this->em
            ->getRepository(CorrectiveAction::class)
            ->findBy(['user' => $this->getUser(), 'status' => 'A traiter']);

        if (!$CorrectivesAction) {

            $message = ['message' => "This user dont have Corrective Action"];

            return new JsonResponse($message, 200);
        }

        $responseArray = [];
        foreach($CorrectivesAction as $CorrectiveAction){
            $responseArray[] = [
                "id" => $CorrectiveAction->getId(),
                "survey_id" => $CorrectiveAction->getQuestion()->getCategory()->getSurvey()->getID(),
                "user_id" => $this->getUser()->getId(),
                "category_id" => $CorrectiveAction->getQuestion()->getCategory()->getID(),
                "question_id" => $CorrectiveAction->getQuestion()->getID(),
                "result_id" => $CorrectiveAction->getResult()->getID(),
                "status" => $CorrectiveAction->getStatus(),
                "image" => $CorrectiveAction->getImage(),
                "comment_question"=> $CorrectiveAction->getCommentQuestion(),
            ];
        }

        return $this->createResponse('CorrectiveAction', $responseArray);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCorrectiveAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $correctiveAction = $this->correctiveActionRepository->getCorrectiveActionByID($data['id']);
        $correctiveAction->setImage($data['image']);
        $correctiveAction->setCommentQuestion($data['comment_question']);
        $correctiveAction->setStatus($data['status']);

        $this->em->persist($correctiveAction);
        $this->em->flush();

        $message = ['message' => 'the corrective action with ID `'.$correctiveAction->getId().'` has been updated .'];
        return new JsonResponse($message, Response::HTTP_CREATED);
    }
}

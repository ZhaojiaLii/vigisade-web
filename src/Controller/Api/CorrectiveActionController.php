<?php

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\CorrectiveAction;
use App\Repository\CorrectiveActionRepository;
use App\Repository\ResultRepository;
use App\Repository\SurveyQuestionRepository;
use App\Service\UploadImageBase64;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CorrectiveActionController extends ApiController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SurveyQuestionRepository
     */
    private $surveyQuestionRepository;

    /**
     * @var CorrectiveActionRepository
     */
    private $correctiveActionRepository;

    /**
     * @var ResultRepository
     */
    private $resultRepository;

    /**
     * @var UploadImageBase64
     */
    private $uploadImageBase64;

    /**
     * CorrectiveActionController constructor.
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param SurveyQuestionRepository $surveyQuestionRepository
     * @param ResultRepository $resultRepository
     * @param CorrectiveActionRepository $correctiveActionRepository
     * @param UploadImageBase64 $uploadImageBase64
     */
    public function __construct(SerializerInterface $serializer,
                                EntityManagerInterface $em,
                                SurveyQuestionRepository $surveyQuestionRepository,
                                ResultRepository $resultRepository,
                                CorrectiveActionRepository $correctiveActionRepository,
                                UploadImageBase64 $uploadImageBase64)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->surveyQuestionRepository = $surveyQuestionRepository;
        $this->resultRepository = $resultRepository;
        $this->correctiveActionRepository = $correctiveActionRepository;
        $this->uploadImageBase64 = $uploadImageBase64;
    }

    /**
     * @return \FOS\RestBundle\View\View|JsonResponse
     */
    public function getCorrectiveActions()
    {
        $correctivesAction = $this->em
            ->getRepository(CorrectiveAction::class)
            ->findBy(['user' => $this->getUser(), 'status' => 'A traiter']);

        if (!$correctivesAction) {

            $message = ['message' => "This user dont have Corrective Action"];

            return new JsonResponse($message, 200);
        }

        $responseArray = [];
        foreach($correctivesAction as $correctiveAction){
            $responseArray[] = [
                "id" => $correctiveAction->getId() ,
                "survey_id" => $correctiveAction->getQuestion() ? $correctiveAction->getQuestion()->getCategory()->getSurvey()->getID() : null,
                "user_id" => $this->getUser() ? $this->getUser()->getId() : null,
                "category_id" => $correctiveAction->getQuestion() ? $correctiveAction->getQuestion()->getCategory()->getID() : null,
                "question_id" => $correctiveAction->getQuestion() ? $correctiveAction->getQuestion()->getID() : null,
                "result_id" => $correctiveAction->getResult() ? $correctiveAction->getResult()->getID() : null,
                "status" => $correctiveAction->getStatus(),
                "image" => $correctiveAction->getImage(),
                "comment_question"=> $correctiveAction->getCommentQuestion(),
            ];
        }

        return $this->createResponse('CorrectiveAction', $responseArray);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function updateCorrectiveAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data)){
            return new JsonResponse(['code' => '400', 'message' => "The JSON sent contains invalid data or empty"], 400);
        }

        // check if `id` exist in data
        if(!array_key_exists('id', $data)) {
            return new JsonResponse(['code' => '400', 'message' => "The parameter `id` should be specified."], 400);
        }

        $correctiveAction = $this->correctiveActionRepository->getCorrectiveActionByID($data['id']);
        $correctiveAction->setCommentQuestion($data['comment_question']);
        $correctiveAction->setStatus($data['status']);

        // get picture with format Base64
        $imageBase64 = $data['image'];

        //get the path to store image result from service.yaml
        $path = $this->getParameter('app.path.action_corrective_images');

        //this service return the name of picture if uploaded true and return false if picture not uploaded
        $imageBase64 = $this->uploadImageBase64->UploadImage($imageBase64, __DIR__.'/../../../'.$path);

        if($imageBase64){
            $correctiveAction->setImage($imageBase64);
        }

        $this->em->persist($correctiveAction);
        $this->em->flush();

        $responseArray = [
            "id" => $correctiveAction->getId()  ,
            "survey_id" => $correctiveAction->getQuestion() ? $correctiveAction->getQuestion()->getCategory()->getSurvey()->getID() : null,
            "user_id" => $this->getUser() ? $this->getUser()->getId() : null,
            "category_id" => $correctiveAction->getQuestion() ? $correctiveAction->getQuestion()->getCategory()->getID() : null,
            "question_id" => $correctiveAction->getQuestion() ? $correctiveAction->getQuestion()->getID() : null,
            "result_id" => $correctiveAction->getResult() ? $correctiveAction->getResult()->getID() : null,
            "status" => $correctiveAction->getStatus(),
            "image" => $correctiveAction->getImage(),
            "comment_question"=> $correctiveAction->getCommentQuestion(),
        ];

        return new JsonResponse($responseArray, Response::HTTP_CREATED);
    }
}

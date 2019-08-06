<?php

namespace App\Controller\Admin;

use App\Entity\SurveyCategory;
use App\Entity\SurveyQuestion;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SurveyQuestionController extends EasyAdminController
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * SurveyController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $surveyCategoryId = $this->request->get('surveyCategoryId');
        $surveyId = $this->request->get('surveyId');

        $response = parent::editAction();
        if ($response instanceof RedirectResponse) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => 'SurveyCategory',
                'id' => $surveyCategoryId,
                'surveyId' => $surveyId,
            ));
        }

        return $response;
    }

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $surveyCategoryId = $this->request->get('surveyCategoryId');
        $surveyId = $this->request->get('surveyId');

        $response = parent::newAction();
        if ($response instanceof RedirectResponse) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => 'SurveyCategory',
                'id' => $surveyCategoryId,
                'surveyId' => $surveyId,
            ));
        }

        return $response;
    }

    /**
     * @return RedirectResponse
     */
    public function deleteQuestionAction()
    {
        $id = $this->request->get('id');

        if ($id) {
            $surveyQuesion = $this->em->getRepository(SurveyQuestion::class)->findOneBy(['id' => $id]);
            $this->em->remove($surveyQuesion);
            $this->em->flush();

            $surveyCategoryId = $this->request->get('surveyCategoryId');
            $surveyId = $this->request->get('surveyId');

            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => 'SurveyCategory',
                'id' => $surveyCategoryId,
                'surveyId' => $surveyId,
            ));
        }

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => 'SurveyCategory'
        ));
    }


    /**
     * @param object $entity
     */
    public function persistEntity($entity)
    {
        $surveyCategoryId = $this->request->get('surveyCategoryId');

        if ($surveyCategoryId) {
            $surveyCategory = $this->em->getRepository(SurveyCategory::class)->findOneBy(['id' => $surveyCategoryId]);

            $surveyQuestions = $this->em->getRepository(SurveyQuestion::class)->findBy(['category' => $surveyCategory], ['questionOrder' => 'DESC']);

            $order = 1;
            if ($surveyQuestions) {
                $order = $surveyQuestions[0]->getQuestionOrder() + 1;
            }

            $entity->setCategory($surveyCategory);
            $entity->setQuestionOrder($order);

            $this->em->persist($entity);
            $this->em->flush();
        }
    }

    /**
     * @param string $actionName
     * @param string $templatePath
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderTemplate($actionName, $templatePath, array $parameters = [])
    {
        $surveyCategoryId = $this->request->get('surveyCategoryId');
        $surveyId = $this->request->get('surveyId');
        $parameters['surveyCategoryId'] = $surveyCategoryId;
        $parameters['surveyId'] = $surveyId;

        return $this->render($templatePath, $parameters);
    }
}

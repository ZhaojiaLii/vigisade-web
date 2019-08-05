<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use App\Entity\SurveyCategory;
use App\Entity\SurveyQuestion;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SurveyCategoryController extends EasyAdminController
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
     * @param object $entity
     */
    public function updateEntity($entity)
    {
        parent::updateEntity($entity);
    }

    /**
     * @param object $entity
     */
    public function persistEntity($entity)
    {
        $surveyId = $this->request->get('surveyId');

        if ($surveyId) {
            $survey = $this->em->getRepository(Survey::class)->findOneBy(['id' => $surveyId]);

            $surveyCategory = $this->em->getRepository(SurveyCategory::class)->findBy(['survey' => $survey], ['categoryOrder' => 'DESC']);

            $order = 1;
            if ($surveyCategory) {
                $order = $surveyCategory[0]->getCategoryOrder() + 1;
            }

            $entity->setSurvey($survey);
            $entity->setCategoryOrder($order);

            parent::persistEntity($entity);

            return;
        }
    }

    public function deleteCategoryAction()
    {
        $id = $this->request->get('id');

        if ($id) {
            $surveyCategory = $this->em->getRepository(SurveyCategory::class)->findOneBy(['id' => $id]);
            $this->em->remove($surveyCategory);
            $this->em->flush();

            $surveyId = $this->request->get('surveyId');

            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => 'Survey',
                'id' => $surveyId,
            ));
        }

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => 'Survey'
        ));
    }

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $surveyId = $this->request->get('surveyId');

        $response = parent::editAction();
        if ($response instanceof RedirectResponse) {
            if ($this->request->request->get('question')) {

                foreach ($this->request->request->get('question') as $id => $question) {
                    $order = $question['ordering'];
                    $surveyQuestion = $this->em->getRepository(SurveyQuestion::class)->findOneBy(['id' => $id]);
                    if ($surveyQuestion) {
                        $surveyQuestion->setQuestionOrder($order);
                        $this->em->persist($surveyQuestion);
                    }
                }
                $this->em->flush();
            }

            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => 'Survey',
                'id' => $surveyId,
            ));
        }
        return $response;
    }

    public function newAction()
    {
        $surveyId = $this->request->get('surveyId');

        $response = parent::newAction();
        if ($response instanceof RedirectResponse) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'entity' => 'Survey',
                'id' => $surveyId,
            ));
        }
        return $response;
    }

    /**
     * @param string $actionName
     * @param string $templatePath
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderTemplate($actionName, $templatePath, array $parameters = [])
    {
        $id = $this->request->get('id');
        $surveyId = $this->request->get('surveyId');

        if ($id) {
            $surveyCategory = $this->em->getRepository(SurveyCategory::class)->findOneBy(['id' => $id]);
            $questions = [];
            $order = [];

            if ($surveyCategory->getQuestions()) {
                foreach ($surveyCategory->getQuestions() as $questionData) {
                    $question = [];
                    $question['id'] = $questionData->getId();
                    $question['title'] = $questionData->getLabel();
                    $question['order'] = $questionData->getQuestionOrder();
                    $questions[] = $question;
                }

                foreach ($questions as $question) {
                    $order[] = $question['order'];
                }

                array_multisort($order, SORT_ASC, $questions);
            }

            $parameters['questions'] = $questions;
            $parameters['surveyCategoryId'] = $id;
        }

        $parameters['surveyId'] = $surveyId;
        
        return $this->render($templatePath, $parameters);
    }
}

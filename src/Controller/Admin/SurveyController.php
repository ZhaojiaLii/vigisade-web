<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use App\Entity\SurveyCategory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SurveyController extends EasyAdminController
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
    public function persistEntity($entity)
    {
        $surveys = $this->em->getRepository(Survey::class)->findBy(['direction' => $entity->getDirection()]);

        if(!$surveys){
            parent::persistEntity($entity);
            return;
        }
        $this->addFlash('danger',  'Formulaire non sauvergardé, la direction est utilisée par le formulaire : '.$surveys[0]->getTitle().' qui possède ID : '.$surveys[0]->getID());
    }

    /**
     * @param object $entity
     */
    public function updateEntity($entity)
    {
        parent::updateEntity($entity);
    }

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $response = parent::editAction();
        if ($response instanceof RedirectResponse) {
            if ($this->request->request->get('category')) {

                foreach ($this->request->request->get('category') as $id => $category) {
                    $order = $category['ordering'];
                    $surveyCategory = $this->em->getRepository(SurveyCategory::class)->findOneBy(['id' => $id]);
                    if ($surveyCategory) {
                        $surveyCategory->setCategoryOrder($order);
                        $this->em->persist($surveyCategory);
                    }
                }
                $this->em->flush();
            }
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

        if ($id) {
            $survey = $this->em->getRepository(Survey::class)->findOneBy(['id' => $id]);
            $categories = [];
            $order = [];
            if ($survey->getCategories()) {
                foreach ($survey->getCategories() as $categoryData) {
                    $countQuestions = count($categoryData->getQuestions());
                    $category['countQuestions'] = $countQuestions;
                    $category['id'] = $categoryData->getId();
                    $category['title'] = $categoryData->getTitle();
                    $category['order'] = $categoryData->getCategoryOrder();
                    $categories[] = $category;
                }

                foreach ($categories as $category) {
                    $order[] = $category['order'];
                }

                array_multisort($order, SORT_ASC, $categories);
                $parameters['surveyId'] = $id;

            }
            $parameters['categories'] = $categories;
        }


        return $this->render($templatePath, $parameters);
    }
}

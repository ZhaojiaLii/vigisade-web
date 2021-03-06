<?php

namespace App\Controller\Admin;

use App\Entity\Result;
use App\Entity\Survey;
use App\Entity\SurveyCategory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
     * @return RedirectResponse
     */
    protected function redirectToReferrer()
    {
        $refererAction = $this->request->query->get('action');
        if ($refererAction == 'new') {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'edit',
                'id' => PropertyAccess::createPropertyAccessor()->getValue($this->request->attributes->get('easyadmin')['item'], $this->entity['primary_key_field_name']),
                'entity' => $this->request->query->get('entity'),
            ));
        } else {
            return parent::redirectToReferrer();
        }
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
            $categories = [];
            $order = [];
            $survey = $this->em->getRepository(Survey::class)->findOneBy(['id' => $id]);
            $result = $this->em->getRepository(Result::class)->findOneBy(['survey' => $survey]);

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
            $parameters['hasResult'] = $result ? true : false;
        }

        return $this->render($templatePath, $parameters);
    }
}

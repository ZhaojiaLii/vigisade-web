<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

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
        $this->addFlash('danger',  'Survey not saved, the direction is used by Survey : '.$surveys[0]->getTitle().' with ID : '.$surveys[0]->getID());
    }

    /**
     * @param object $entity
     */
    public function updateEntity($entity)
    {
        parent::updateEntity($entity);
    }
}

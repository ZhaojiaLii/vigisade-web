<?php

namespace App\Controller\Admin;

use App\Entity\Direction;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class DirectionController extends EasyAdminController
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
        $direction = $this->em->getRepository(Direction::class)->findBy([
            'name' => $entity->getName()
        ]);

        if(!$direction){
            parent::persistEntity($entity);

            return;
        }

        $this->addFlash('danger',  'Direction non sauvegardée, le nom est utilisé par : '.$direction[0]->getName().' qui possède ID : '.$direction[0]->getID());
    }

    /**
     * @param object $entity
     */
    public function updateEntity($entity)
    {
        parent::updateEntity($entity);
    }
}

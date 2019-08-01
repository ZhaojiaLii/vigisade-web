<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AreaController extends EasyAdminController
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
        $area = $this->em->getRepository(Area::class)->findBy([
            'name' => $entity->getName()
        ]);

        if(!$area){
            parent::persistEntity($entity);

            return;
        }

        $this->addFlash('danger',  'Area not saved, the name is used by this Area : '.$area[0]->getName().' with ID : '.$area[0]->getID());
    }

    /**
     * @param object $entity
     */
    public function updateEntity($entity)
    {
        parent::updateEntity($entity);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Entity;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class EntityController extends EasyAdminController
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
        $oEntity = $this->em->getRepository(Entity::class)->findBy([
            'name' => $entity->getName()
        ]);

        if(!$oEntity){
            parent::persistEntity($entity);

            return;
        }

        $this->addFlash('danger',  'Entité non sauvegardée, le nom est utilisé par : '.$oEntity[0]->getName().' qui possède ID : '.$oEntity[0]->getID());
    }

    /**
     * @param object $entity
     */
    public function updateEntity($entity)
    {
        parent::updateEntity($entity);
    }
}
